<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Model\Entity;

use Magento\Framework\Model\AbstractModel;

interface EntityKeyStrategyInterface
{

    /**
     * Getting key for entity
     *
     * @param AbstractModel $entity
     * @return string
     */
    public function getKey($entity);
}
