<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class EAVBeforeSaveEntity implements ObserverInterface
{
    /**
     * @var EntityManagement
     */
    protected $newEntityManagement;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @param EntityManagement $newEntityManagement
     * @param string           $entityName
     */
    public function __construct(
        EntityManagement $newEntityManagement,
        string           $entityName = 'object'
    ) {
        $this->entityName = $entityName;
        $this->newEntityManagement = $newEntityManagement;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $entity = $this->getEntity($observer);
        if ($entity->isObjectNew()) {
            try {
                $this->newEntityManagement->registerEntity($this->entityName, $entity);
            } catch (\Exception $e) {
                return;
            }
        }
    }

    /**
     * Getting entity from observer
     *
     * @param Observer $observer
     * @return array|mixed|null
     */
    public function getEntity(Observer $observer)
    {
        return $observer->getEvent()->getData($this->entityName);
    }
}
