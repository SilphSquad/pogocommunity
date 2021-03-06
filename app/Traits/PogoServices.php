<?php

namespace App\Traits;

use App\Services\PokemonService;
use App\Services\TrainerService;
use App\Services\UserService;

trait PogoServices
{
    private $services = [];

    // *** Generic get/load/set *** //

    private function getService($key)
    {
        if (!$this->serviceExists($key)) {
            throw new \Exception('Failed to load service with key: '.$key);
        }

        return $this->services[$key];
    }

    private function setService($key, $object)
    {
        $this->services[$key] = $object;
    }

    private function serviceExists($key)
    {
        return array_key_exists($key, $this->services);
    }

    private function loadService($service)
    {
        if ($this->serviceExists($service)) {
            return $this->getService($service);
        }

        $serviceName = $this->getServiceName($service);

        $serviceClass = resolve($serviceName);

        $this->setService($service, $serviceClass);

        return $this->getService($service);
    }

    // ** Map names to classes ** //

    private function getServiceName($serviceKey)
    {
        if (class_exists("App\\Services\\".$serviceKey)) {
            $serviceName = "App\\Services\\".$serviceKey;
        } else {
            throw new \Exception('Failed to load service class: '.$serviceKey);
        }
        return $serviceName;
    }

    // ** Get specific classes ** //

    /**
     * @return PokemonService
     */
    public function getServicePokemon()
    {
        return $this->loadService('PokemonService');
    }

    /**
     * @return UserService
     */
    public function getServiceUser()
    {
        return $this->loadService('UserService');
    }

    /**
     * @return TrainerService
     */
    public function getServiceTrainer()
    {
        return $this->loadService('TrainerService');
    }

}
