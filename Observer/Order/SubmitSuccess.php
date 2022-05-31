<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Observer\Order;

use \Beehexa\Webhook\Observer\Events\Update;
use Exception;
use Magento\Framework\Event\Observer;

class SubmitSuccess extends Update
{

    /**
     * @param Observer $observer
     * @return void
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        // If the order new we will push the update to webhook.
        // Because sales_order_save_after is called before this event.
        if ($this->isEntityNew($this->getEntityModel($observer))) {
            parent::execute($observer);
        }
    }
}
