<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Observer\Order;

use Beehexa\Webhook\Observer\Events\Update as DefaultUpdate;
use Magento\Framework\Event\Observer;

class Update extends DefaultUpdate
{

    public function execute(Observer $observer)
    {
        if (!($this->isEntityNew($this->getEntityModel($observer)))) {
            parent::execute($observer);
        }
    }
}
