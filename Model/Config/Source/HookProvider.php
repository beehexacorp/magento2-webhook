<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class HookProvider implements ArrayInterface
{
    const SLACK_PROVIDER_NAME    = 'slack';
    const HEXASYNC_PROVIDER_NAME = 'hexasync';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
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
        return [self::HEXASYNC_PROVIDER_NAME => __('HexaSync'), self::SLACK_PROVIDER_NAME => __('Slack')];
    }

}
