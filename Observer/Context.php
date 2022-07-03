<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Observer;

use Beehexa\Webhook\Helper\HookHelper as HelperData;
use Beehexa\WebhookPhp\Hook\Data\MessageBuilder;
use Psr\Log\LoggerInterface;

class Context
{
    /**
     * @var WebhookRegistry
     */
    protected $webhookRegistry;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var MessageBuilder
     */
    protected $messageBuilder;

    /**
     * @param HelperData      $helperData
     * @param MessageBuilder  $messageBuilder
     * @param WebhookRegistry $webhookRegistry
     * @param LoggerInterface $logger
     */
    public function __construct(
        HelperData      $helperData,
        MessageBuilder  $messageBuilder,
        WebhookRegistry $webhookRegistry,
        LoggerInterface $logger
    ) {
        $this->helperData = $helperData;
        $this->webhookRegistry = $webhookRegistry;
        $this->messageBuilder = $messageBuilder;
        $this->logger = $logger;
    }

    /**
     * Getter for helper
     *
     * @return HelperData
     */
    public function getHelperData(): HelperData
    {
        return $this->helperData;
    }

    /**
     * Getter for Logger
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Getter for webhook
     *
     * @return WebhookRegistry
     */
    public function getWebhookRegistry(): WebhookRegistry
    {
        return $this->webhookRegistry;
    }

    /**
     * Getter for message builder
     *
     * @return MessageBuilder
     */
    public function getMessageBuilder(): MessageBuilder
    {
        return $this->messageBuilder;
    }
}
