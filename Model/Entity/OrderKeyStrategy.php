<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Model\Entity;

class OrderKeyStrategy implements EntityKeyStrategyInterface
{
    /**
     * @param \Magento\Sales\Model\Order $entity
     * @return string
     */
    public function getKey($entity)
    {
        return implode('--', [
            $entity->getQuoteId(),
            $entity->getCustomerEmail()
        ]);
    }
}
