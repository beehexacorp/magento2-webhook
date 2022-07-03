<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Observer\Events;

class Delete extends DefaultEntity
{
    /**
     * @inheritDoc
     */
    public function createBuilder($entity)
    {
        $this->messageBuilder->addField('event_name', 'delete');
        return parent::createBuilder($entity);
    }
}
