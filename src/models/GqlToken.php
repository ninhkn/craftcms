<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\models;

use Craft;
use craft\base\Model;
use craft\helpers\DateTimeHelper;
use craft\records\GqlToken as GqlSchemaRecord;
use craft\validators\UniqueValidator;
use DateTime;

/**
 * GraphQL token class
 *
 * @property bool $isPublic Whether this is the public token
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.4.0
 */
class GqlToken extends Model
{
    /**
     * The public access token value.
     */
    public const PUBLIC_TOKEN = '__PUBLIC__';

    /**
     * @var int|null ID
     */
    public ?int $id = null;

    /**
     * @var string|null Token name
     */
    public ?string $name = null;

    /**
     * @var int|null ID of the associated schema.
     * @since 3.4.0
     */
    public ?int $schemaId = null;

    /**
     * @var string The access token
     */
    public string $accessToken;

    /**
     * @var bool Is the token enabled
     */
    public bool $enabled = true;

    /**
     * @var DateTime|null Date expires
     */
    public ?DateTime $expiryDate = null;

    /**
     * @var DateTime|null Date last used
     */
    public ?DateTime $lastUsed = null;

    /**
     * @var DateTime|null Date created
     */
    public ?DateTime $dateCreated = null;

    /**
     * @var string|null $uid
     */
    public ?string $uid = null;

    /**
     * @var array|null The allowed scope for the token.
     */
    private ?array $_scope = null;

    /**
     * @var GqlSchema|null The schema for this token.
     */
    private ?GqlSchema $_schema = null;

    /**
     * @var bool Whether this is a temporary token
     */
    public bool $isTemporary = false;

    public function __construct($config = [])
    {
        // If the scope is passed in, intercept it and use it.
        if (!empty($config['schema'])) {
            $this->_schema = $config['schema'];

            // We don't want any confusion here, so unset the schema ID, if they set a custom scope.
            unset($config['schemaId']);
        }

        unset($config['schema']);
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    protected function defineRules(): array
    {
        $rules = parent::defineRules();
        $rules[] = [['name', 'accessToken'], 'required'];
        $rules[] = [
            ['name', 'accessToken'],
            UniqueValidator::class,
            'targetClass' => GqlSchemaRecord::class,
        ];

        return $rules;
    }

    /**
     * Use the translated group name as the string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Returns whether the token is enabled, hasn't expired, and has a schema assigned to it.
     *
     * @return bool
     * @since 3.4.13
     */
    public function getIsValid(): bool
    {
        return $this->enabled && !$this->getIsExpired() && $this->getSchema() !== null;
    }

    /**
     * Returns whether the token has expired.
     *
     * @return bool
     * @since 3.4.5
     */
    public function getIsExpired(): bool
    {
        return $this->expiryDate && $this->expiryDate->getTimestamp() <= DateTimeHelper::currentTimeStamp();
    }

    /**
     * Returns whether this is the public token.
     *
     * @return bool
     */
    public function getIsPublic(): bool
    {
        return $this->accessToken === self::PUBLIC_TOKEN;
    }

    /**
     * Return the schema for this token.
     *
     * @return GqlSchema|null
     */
    public function getSchema(): ?GqlSchema
    {
        if (empty($this->_schema) && !empty($this->schemaId)) {
            $this->_schema = Craft::$app->getGql()->getSchemaById($this->schemaId);
        }

        return $this->_schema;
    }

    /**
     * Sets the schema for this token.
     *
     * @param GqlSchema $schema
     * @since 3.5.0
     */
    public function setSchema(GqlSchema $schema): void
    {
        $this->_schema = $schema;
        $this->schemaId = $schema->id;
    }

    /**
     * Return the schema's scope for this token.
     *
     * @return mixed
     */
    public function getScope(): mixed
    {
        if (!isset($this->_scope)) {
            $schema = $this->getSchema();
            $this->_scope = $schema->scope ?? null;
        }

        return $this->_scope;
    }
}
