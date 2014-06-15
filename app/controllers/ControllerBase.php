<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\DispatcherInterface;

abstract class ControllerBase extends Controller
{
    const ERROR_CODE_OK = 200;
    const ERROR_CODE_NOT_FOUND = 404;
    const ERROR_CODE_RESOURCE_UNAVAILABLE = 410;
    const ERROR_CODE_MAINTENANCE = 503;


    protected $hashParams = array(
        'code'      => null,
        'status'    => null,
        'msg'       => null,
        'data'      => array()
    );

    public function initialize()
    {
        $this->view->disable();
    }

    public function beforeExecuteRoute(DispatcherInterface $objDispatcher)
    {
        $this->hashParams['code'] = self::ERROR_CODE_OK;
    }

    public function afterExecuteRoute(DispatcherInterface $dispatcher)
    {
        if (is_null($this->hashParams['code'])) {
            $this->hashParams['code'] = self::ERROR_CODE_RESOURCE_UNAVAILABLE;
        }

        if (is_null($this->hashParams['status'])) {
            $this->hashParams['status'] = $this->hashParams['code'] === self::ERROR_CODE_OK;
        }

        switch ($this->hashParams['code']) {
            case self::ERROR_CODE_OK:
                $this->hashParams['msg'] = 'OK';
                break;

            case self::ERROR_CODE_MAINTENANCE:
                $this->hashParams['msg'] = 'Service Unavailable';
                break;

            case self::ERROR_CODE_NOT_FOUND:
                $this->hashParams['msg'] = 'Not Found';
                break;

            case self::ERROR_CODE_RESOURCE_UNAVAILABLE:
            default:
                $this->hashParams['msg'] = 'Resource Unavailable';
                break;
        }

        $this->response
            ->setStatusCode($this->hashParams['code'], $this->hashParams['msg'])
            ->setJsonContent($this->hashParams)
            ->send();
    }

    /**
     * Allow to forward the current process to another action in another controller
     *
     * @param string $strController controller's name
     * @param string $strAction action's name
     * @param array $hashParams optional parameters
     */
    protected function forward($strController, $strAction, $hashParams = array())
    {
        $hashParams = array(
            'controller'    => $strController,
            'action'        => $strAction,
            'params'        => http_build_query($hashParams)
        );
        return $this->dispatcher->forward($hashParams);
    }

    /**
     * Allow to redirect the current process to another action in another controller
     *
     * @param string $strController controller's name
     * @param string $strAction action's name
     * @param array $hashParams optional parameters
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    protected function redirect($strController, $strAction, $hashParams = array())
    {
        $strRoute = $this->di->getShared('url')->get(array(
            'for'           => 'public',
            'controller'    => $strController,
            'action'        => $strAction,
            'params'        => http_build_query($hashParams)
        ));
        return $this->response->redirect($strRoute, true, 200);
    }
}
