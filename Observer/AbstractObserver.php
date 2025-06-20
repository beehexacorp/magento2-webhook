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

    /**
     * @var EntityManagement
     */
    protected $entityManagement;

    /**
     * @param Context          $context
     * @param EntityManagement $entityManagement
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
     * Getter for entity field name
     *
     * @return string
     */
    abstract public function getEntityFieldName();

    /**
     * Getting entity name
     *
     * @return string
     */
    abstract public function getEntityName();

    /**
     * @inheritDoc
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
     * Getting entity model from observer
     *
     * @param Observer $observer
     * @return AbstractModel|null
     * @throws LocalizedException
     */
    public function getEntityModel(Observer $observer)
    {
        $entity = $observer->getEvent()->getData($this->getEntityFieldName());
        if (null === $entity) {
            throw new LocalizedException(__("Can not load entity model from observer"));
        }
        return $entity;
    }

    /**
     * Publishing entity
     *
     * @param AbstractModel $entity
     * @return bool
     * @throws LocalizedException
     */
    public function push($entity)
    {
        $this->messageBuilder->clear();
        return $this->entityManagement->push($this->getEntityName(), $entity, $this);
    }

    /**
     * @inheritDoc
     */
    public function createBuilder($entity)
    {
        $messageBuilder = $this->messageBuilder;
        $messageBuilder->setText($this->getEntityName());
        $messageBuilder->setEntityId($entity->getId());
        $messageBuilder->addField('entity_type', $this->getEntityName());
        $messageBuilder->addField('entity_id', $entity->getId());
        $messageBuilder->addField('updated_at', $entity->getData('updated_at') ?? null);
        return $messageBuilder;
    }
}
