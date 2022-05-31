<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Observer\Events;

class Update extends DefaultEntity
{
    /**
     * @inheirtDoc
     */
    public function createBuilder($entity)
    {
        $messageBuilder = parent::createBuilder($entity);
        $messageBuilder->addField('event_name', 'update');
        if ($this->isEntityNew($entity) || $entity->isObjectNew()) {
            $messageBuilder->addField('event_name', 'new');
        }
        return $messageBuilder;
    }
}
