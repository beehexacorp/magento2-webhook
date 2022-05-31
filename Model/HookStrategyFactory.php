<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Model;

use Beehexa\Webhook\Hook\Beehexa;
use Beehexa\Webhook\Model\Config\Source\HookProvider;
use Beehexa\WebhookPhp\Hook\HookStrategyInterface;
use Beehexa\WebhookPhp\Hook\Strategy\DefaultChannel;
use Beehexa\WebhookPhp\Hook\Strategy\SlackChannel;
use Magento\Framework\ObjectManagerInterface;

class HookStrategyFactory
{

    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Factory constructor
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param       $provider
     * @param array $data
     * @return HookStrategyInterface
     */
    public function create($provider, array $data = [])
    {
        $_instanceName = DefaultChannel::class;
        switch ($provider) {
            case HookProvider::SLACK_PROVIDER_NAME:
                $_instanceName = SlackChannel::class;
                break;
            case HookProvider::HEXASYNC_PROVIDER_NAME:
                $_instanceName = Beehexa::class;
                break;
            default:
        }

        return $this->_objectManager->create($_instanceName, $data);
    }
}
