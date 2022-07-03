<?php
/*
 * Copyright © 2022 Beehexa All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Beehexa\Webhook\Helper;

use Beehexa\Base\Helper\Data;

class HookHelper extends Data
{
    /**
     * Is webhook enabled
     *
     * @return boolean
     */
    public function isEnable()
    {
        return $this->getConfigFlag('webhook/enable');
    }

    /**
     * Getting endpoint provider
     *
     * @return string
     */
    public function getEndpointProvider()
    {
        return $this->getConfigValue('webhook/endpoint_provider');
    }

    /**
     * Getting endpoint URL
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getConfigValue('webhook/hook_endpoint');
    }
}
