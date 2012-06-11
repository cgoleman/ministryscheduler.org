<?php

class LogoutController extends Zend_Controller_Action
{

    protected $_redirector = null;
 
    public function init()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
        Zend_Registry::_unsetInstance();
 
        // Set the default options for the redirector
        // Since the object is registered in the helper broker, these
        // become relevant for all actions from this point forward
        $this->_redirector->setCode(303)
                          ->setExit(false)
                          ->setGotoSimple("index",
                                          "login");
    }

    public function indexAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        
        $this->_redirector->redirectAndExit();
        return;
    }


}

