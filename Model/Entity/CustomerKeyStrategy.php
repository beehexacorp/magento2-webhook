<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Model\Entity;

class CustomerKeyStrategy implements EntityKeyStrategyInterface
{
    /**
     * @param \Magento\Customer\Model\Customer $entity
     * @return string
     */
    public function getKey($entity)
    {
        return $entity->getEmail();
    }
}
