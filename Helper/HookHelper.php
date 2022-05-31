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
     * @return boolean
     */
    public function isEnable()
    {
        return $this->getConfigFlag('webhook/enable');
    }

    /**
     * @return string
     */
    public function getEndpointProvider()
    {
        return $this->getConfigValue('webhook/endpoint_provider');
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        $serviceEndpoint = $this->getConfigValue('webhook/test_endpoint');
        if ($this->getConfigFlag('webhook/is_production')) {
            $serviceEndpoint = $this->getConfigValue('webhook/prod_endpoint');
        }
        return $serviceEndpoint;
    }
}
