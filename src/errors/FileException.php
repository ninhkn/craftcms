<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\errors;

use yii\base\UserException;

/**
 * Class FileException
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.0.0
 */
class FileException extends UserException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName(): string
    {
        return 'File Exception';
    }
}
