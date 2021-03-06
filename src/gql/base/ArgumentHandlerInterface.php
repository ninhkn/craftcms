<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\gql\base;

use craft\gql\ArgumentManager;

/**
 * ArgumentHandlerInterface defines the common interface to be implemented by all argument handler classes.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.6.0
 */
interface ArgumentHandlerInterface
{
    /**
     * Handle an argument collection
     *
     * @param array $argumentList argument list to be used for the query
     * @return array
     */
    public function handleArgumentCollection(array $argumentList = []): array;

    /**
     * Set the current argument manager. Required for recursive argument preparation.
     *
     * @param ArgumentManager $argumentManager
     */
    public function setArgumentManager(ArgumentManager $argumentManager): void;
}
