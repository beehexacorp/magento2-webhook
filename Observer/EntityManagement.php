<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Observer;

use Beehexa\Webhook\Helper\HookHelper as HelperData;
use Beehexa\Webhook\Model\Entity\EntityKeyStrategyInterface;
use Beehexa\Webhook\Model\HookStrategyFactory;
use Beehexa\Webhook\Model\MessageBuilderInterface;
use Beehexa\WebhookPhp\Hook\Data\MessageBuilder;
use Beehexa\WebhookPhp\HookContext;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Psr\Log\LoggerInterface;

/**
 * Use 1 class because break down this class isn't necessary
 */
class EntityManagement
{
    /**
     * @var WebhookRegistry
     */
    protected $webhookRegistry;

    /**
     * @var EntityKeyStrategyInterface[]|null
     */
    protected $newEntityStrategies;

    /**
     * @var MessageBuilder
     */
    protected $messageBuilder;

    /**
     * @var HookStrategyFactory
     */
    protected $hookStrategyFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var HookContext
     */
    protected $hookContext;

    /**
     * @param Context                      $context
     * @param MessageBuilder               $messageBuilder
     * @param HookStrategyFactory          $hookStrategyFactory
     * @param EntityKeyStrategyInterface[] $newEntityStrategies
     */
    public function __construct(
        Context             $context,
        MessageBuilder      $messageBuilder,
        HookStrategyFactory $hookStrategyFactory,
        ?array              $newEntityStrategies
    ) {
        $this->messageBuilder = $messageBuilder;
        $this->hookStrategyFactory = $hookStrategyFactory;
        $this->webhookRegistry = $context->getWebhookRegistry();
        $this->newEntityStrategies = $newEntityStrategies ?: [];
        $this->logger = $context->getLogger();
        $this->helperData = $context->getHelperData();
    }

    /**
     * Get entity strategy
     *
     * @param string $entityName
     * @return EntityKeyStrategyInterface
     * @throws LocalizedException
     */
    protected function getStrategy(string $entityName): ?EntityKeyStrategyInterface
    {
        if (isset($this->newEntityStrategies[$entityName])) {
            $strategy = $this->newEntityStrategies[$entityName];
            if (!($strategy instanceof EntityKeyStrategyInterface)) {
                throw new LocalizedException(__("New Entity Strategy must instance of NewEntityStrategyInterface"));
            }
        } else {
            throw new LocalizedException(__("Undefined New Entity Strategy: %s", $entityName));
        }
        return $strategy;
    }

    /**
     * Checking if is new entity
     *
     * @param string        $entityName
     * @param AbstractModel $entity
     * @return bool
     * @throws LocalizedException
     */
    public function isObjectNew(string $entityName, AbstractModel $entity): bool
    {
        $newEntities = $this->webhookRegistry->registry('new_' . $entityName);
        $strategy = $this->getStrategy($entityName);
        if ($strategy) {
            $entityKey = $strategy->getKey($entity);
            if ($newEntities) {
                return !!in_array($entityKey, $newEntities);
            }
        }
        return false;
    }

    /**
     * Registering an entity
     *
     * @param string        $entityName
     * @param AbstractModel $entity
     * @return void
     * @throws LocalizedException
     */
    public function registerEntity(string $entityName, AbstractModel $entity)
    {
        $strategy = $this->getStrategy($entityName);
        if ($strategy) {
            $entityKey = $strategy->getKey($entity);
            $registerKey = 'new_' . $entityName;
            $newEntities = $this->webhookRegistry->registry($registerKey);
            if (!$newEntities) {
                $newEntities = [];
            }
            $newEntities[] = $entityKey;
            $this->webhookRegistry->unregister($registerKey);
            $this->webhookRegistry->register($registerKey, $newEntities);
        }
    }

    /**
     * Getting/Creating hook content
     *
     * @return HookContext
     * @throws LocalizedException
     */
    public function getHookContext()
    {
        if (!$this->hookContext) {
            $serviceEndpoint = $this->helperData->getEndpoint();
            $endpointProvider = $this->helperData->getEndpointProvider();
            if ($endpointProvider && $serviceEndpoint) {
                $apiToken = $this->helperData->getApiToken();
                $hookStrategy = $this->hookStrategyFactory->create(
                    $endpointProvider,
                    [
                        'serviceEndpoint' => $serviceEndpoint,
                        'apiToken' => $apiToken
                    ]
                );
                $this->hookContext = new HookContext($hookStrategy);
            } else {
                throw new LocalizedException(__("Please config endpoint and hook service provider"));
            }
        }
        return $this->hookContext;
    }

    /**
     * Check if the entity is published
     *
     * @param string $entityName
     * @param AbstractModel $entity
     * @return bool
     * @throws LocalizedException
     */
    public function isPublished(string $entityName, $entity)
    {
        $strategy = $this->getStrategy($entityName);
        if ($strategy) {
            $entityKey = $strategy->getKey($entity);
            $publishedItems = $this->webhookRegistry->registry('publish_' . $entityName);
            if ($publishedItems) {
                return in_array($entityKey, $publishedItems);
            }
        }
        return false;
    }

    /**
     * Push entity to webhook
     *
     * @param string                  $entityName
     * @param AbstractModel           $entity
     * @param MessageBuilderInterface $builder
     * @return bool
     * @throws LocalizedException
     */
    public function push($entityName, $entity, MessageBuilderInterface $builder)
    {
        $isPublished = $this->isPublished($entityName, $entity);
        if (!$isPublished) {
            $messageBuilder = $builder->createBuilder($entity);
            $message = $messageBuilder->build();
            $isPublished = $this->getHookContext()->exec($message);
            $strategy = $this->getStrategy($entityName);
            if ($strategy) {
                $entityKey = $strategy->getKey($entity);
                $registerKey = 'publish_' . $entityName;
                $publishedItems = $this->webhookRegistry->registry($registerKey);
                if (!$publishedItems) {
                    $publishedItems = [];
                }
                $publishedItems[] = $entityKey;
                $this->webhookRegistry->unregister($registerKey);
                $this->webhookRegistry->register($registerKey, $publishedItems);
            }
        }
        return $isPublished;
    }
}
