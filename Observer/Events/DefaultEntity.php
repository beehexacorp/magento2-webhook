<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Observer\Events;

use Beehexa\Webhook\Observer\AbstractObserver;
use Beehexa\Webhook\Observer\Context;
use Beehexa\Webhook\Observer\EntityManagement;
use Exception;
use Magento\Framework\Model\AbstractModel;

class DefaultEntity extends AbstractObserver
{
    /**
     * @var string|null
     */
    protected $entityName;

    /**
     * @var string|null
     */
    protected $entityKeyName;

    /**
     * @param Context          $context
     * @param EntityManagement $entityManagement
     * @param string           $entityName
     * @param string|null      $entityKeyName
     */
    public function __construct(
        Context          $context,
        EntityManagement $entityManagement,
        string           $entityName,
        ?string          $entityKeyName = null
    ) {
        $this->entityName = $entityName;
        $this->entityKeyName = $entityKeyName;
        parent::__construct($context, $entityManagement);
    }

    /**
     * @inheritDoc
     */
    public function getEntityName()
    {
        if (!$this->entityName) {
            throw new \LogicException(__("Invalid Entity Name"));
        }
        return $this->entityName;
    }

    /**
     * @inheritDoc
     */
    public function getEntityFieldName()
    {
        if (!$this->entityKeyName) {
            $this->entityKeyName = $this->entityName;
        }
        return $this->entityKeyName;
    }

    /**
     * Validate is object new
     *
     * @param AbstractModel $entity
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isEntityNew($entity)
    {
        return $this->entityManagement->isObjectNew($this->entityName, $entity);
    }
}
