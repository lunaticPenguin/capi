<?php

namespace App\Plugins;

use Phalcon\Mvc\User\Plugin;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;

class CustomDispatcher extends Plugin {

	/**
	 * Plugin constructor
	 * @param \Phalcon\DI $objDi
	 */
    public function __construct(\Phalcon\DI $objDi) {
        $this->di = $objDi;
    }
    
    /**
     * Code executed before an exception is thrown
     * 
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return boolean false
     */
    public function beforeException(Event $objEvent, Dispatcher $objDispatcher, \Exception $objException) {

        switch ($objException->getCode()) {
    		case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
    		case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
    		case Dispatcher::EXCEPTION_INVALID_HANDLER:
    			$objDispatcher->forward(
    				array(
    					'namespace'		=> 'App\Controllers',
    					'controller' 	=> 'error',
    					'action'     	=> 'show410',
    				)
    			);
    			return false;
    			break;
    	}
    }
    
    /**
     * Code executed before route dispatching
     */
    public function beforeDispatchLoop() {

    	// storing all GET params with key/value
    	$arrayParams = $this->dispatcher->getParams();
    	$hashResult = array();
    	foreach ($arrayParams as $intKey => $mixedValue) {
    		if ($intKey & 1) {
    			$hashResult[$arrayParams[$intKey - 1]] = $mixedValue;
    		}
    	}
       	$this->dispatcher->setParams($hashResult);
    }
    
    /**
     * Code executed before controller call
     */
    public function beforeExecuteRoute() {

    	// check for maintenance
    	$objConfig = $this->di->get('config');
    	if ($objConfig->application->maintenance === true) {
    		if ($this->dispatcher->getControllerName() !== 'error'
    			&&
    			$this->dispatcher->getActionName() !== 'maintenance') {
	    		$this->dispatcher->forward(
	    			array(
		    			'namespace'		=> 'App\Controllers',
		    			'controller' 	=> 'error',
		    			'action'     	=> 'maintenance',
	    			)
	    		);
    		}
    	}
    }
}