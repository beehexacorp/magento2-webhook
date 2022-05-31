<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Observer;

use Beehexa\Webhook\Helper\HookHelper as HelperData;
use Beehexa\Webhook\Model\MessageBuilderInterface;
use Beehexa\WebhookPhp\Hook\Data\MessageBuilder;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

abstract class AbstractObserver implements ObserverInterface, MessageBuilderInterface
{
    /**
     * @var Observer
     */
    protected $observer;

    /**
     * @var MessageBuilder
     */
    protected $messageBuilder;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    protected $entityManagement;

    /**
     * @param Context $context
     */
    public function __construct(
        Context          $context,
        EntityManagement $entityManagement
    ) {
        $this->helperData = $context->getHelperData();
        $this->logger = $context->getLogger();
        $this->entityManagement = $entityManagement;
        $this->messageBuilder = $context->getMessageBuilder();
    }

    /**
     * @return string
     */
    public abstract function getEntityFieldName();

    /**
     * @return string
     */
    public abstract function getEntityName();

    /**
     * @param Observer $observer
     * @return void
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        $this->observer = $observer;
        $isPublished = false;
        if ($this->helperData->isEnable()) {
            try {
                $entity = $this->getEntityModel($observer);
                $isPublished = $this->push($entity);
            } catch (\Exception $e) {
                $this->logger->critical($e);
            } finally {
                if (!$isPublished) {
                    $this->logger->critical("Has an error during publish to webhook");
                    // This for re-push the entity if we got any problem.
                }
                return;
            }
        }
    }

    /**
     * @param Observer $observer
     * @return AbstractModel|null
     * @throws \Exception
     */
    public function getEntityModel(Observer $observer)
    {
        $entity = $observer->getEvent()->getData($this->getEntityFieldName());
        if(null === $entity){
            throw new LocalizedException(__("Can not load entity model from observer"));
        }
        return $entity;
    }

    /**
     * @param AbstractModel $entity
     * @return bool
     * @throws \Exception
     */
    public function push($entity)
    {
        $this->messageBuilder->clear();
        return $this->entityManagement->push($this->getEntityName(), $entity, $this);
    }

    /**
     * @param AbstractModel $entity
     * @return MessageBuilder
     * @throws \Exception
     */
    public function createBuilder($entity)
    {
        $messageBuilder = $this->messageBuilder;
        $messageBuilder->setText($this->getEntityName());
        $messageBuilder->setEntityId($entity->getId());
        $messageBuilder->addField('entity_type', $this->getEntityName());
        $messageBuilder->addField('entity_id', $entity->getId());
        return $messageBuilder;
    }

}
