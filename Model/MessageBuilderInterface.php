<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Model;

use Beehexa\WebhookPhp\Hook\Data\MessageBuilder;
use Magento\Framework\Model\AbstractModel;

interface MessageBuilderInterface
{
    /**
     * Creating a builder
     *
     * @param AbstractModel $entity
     * @return MessageBuilder
     */
    public function createBuilder($entity);
}
