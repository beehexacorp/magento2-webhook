<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class HookProvider implements ArrayInterface
{
    public const SLACK_PROVIDER_NAME    = 'slack';
    public const HEXASYNC_PROVIDER_NAME = 'hexasync';
    public const DEFAULT_PROVIDER_NAME  = 'default';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::DEFAULT_PROVIDER_NAME, 'label' => __('Default')],
            ['value' => self::HEXASYNC_PROVIDER_NAME, 'label' => __('HexaSync')],
            ['value' => self::SLACK_PROVIDER_NAME, 'label' => __('Slack')]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::DEFAULT_PROVIDER_NAME  => __('Default'),
            self::HEXASYNC_PROVIDER_NAME => __('HexaSync'),
            self::SLACK_PROVIDER_NAME    => __('Slack')];
    }
}
