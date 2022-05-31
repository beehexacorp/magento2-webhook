<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Model\Entity;

class ProductKeyStrategy implements EntityKeyStrategyInterface
{
    /**
     * @param \Magento\Catalog\Model\Product $entity
     * @return string
     */
    public function getKey($entity)
    {
        return $entity->getSku() . implode('-', $entity->getWebsiteIds());
    }
}
