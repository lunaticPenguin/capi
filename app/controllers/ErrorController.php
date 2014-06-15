<?php

namespace App\Controllers;

use Phalcon\Mvc\DispatcherInterface;

class ErrorController extends ControllerBase
{
    public function beforeExecuteRoute(DispatcherInterface $objDispatcher)
    {
        parent::beforeExecuteRoute($objDispatcher);
        unset($this->hashParams['data']);
    }

    public function show410Action()
    {
        $this->hashParams['code'] = self::ERROR_CODE_RESOURCE_UNAVAILABLE;
    }

    public function maintenanceAction()
    {
        $this->hashParams['code'] = self::ERROR_CODE_MAINTENANCE;
    }
}

