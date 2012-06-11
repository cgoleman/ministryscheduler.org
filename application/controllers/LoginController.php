<?php

class LoginController extends Zend_Controller_Action
{
	/**
	 * 
	 * @var Zend_Auth
	 */
	protected $_auth = null;
	
	protected $_redirector = null;

    public function init()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');

        $this->view->loggedIn = 0;
        
        $this->_auth = Zend_Auth::getInstance();
        if ($this->_auth->hasIdentity()) {
        	$this->view->loggedIn = 1;
        }
    }

    public function indexAction()
    {
        
    	$request = $this->getRequest();
    	$form    = new Application_Form_Login();
    	
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($request->getPost())) {
    			
    	    	//attempt to authenticate user
    			$authAdapter = new BL_Auth_Adapter($form->getValue("Username"), $form->getValue("Password"));
    			
    			// Attempt authentication, saving the result
    			$result = $this->_auth->authenticate($authAdapter);
    			
    			if (!$result->isValid()) {
    				// Authentication failed; print the reasons why
    				foreach ($result->getMessages() as $message) {
    					echo "$message\n";
    				}
    			}
    			$this->_redirector->setCode(303)
    			  ->setExit(false)
    			  ->setGotoSimple("index","class");
    			$this->_redirector->redirectAndExit();
    			return;
    		}
    		$this->view->form = $form;
    	}
    	$this->view->form = $form;
    }
    
    public function authenticateAction()
    {
    	$request = $this->getRequest();
    	$form    = new Application_Form_Login();
    	
    	if ($this->getRequest()->isPost()) {
    		if ($form->isValid($request->getPost())) {
    			
    	    	//attempt to authenticate user
    			$authAdapter = new BL_Auth_Adapter($form->getValue("Username"), $form->getValue("Password"));
    			
    			// Attempt authentication, saving the result
    			$result = $this->_auth->authenticate($authAdapter);
    			
    			if (!$result->isValid()) {
    				// Authentication failed; print the reasons why
    				foreach ($result->getMessages() as $message) {
    					echo "$message\n";
    				}
    			}
    			$this->_redirector->setCode(303)
    			  ->setExit(false)
    			  ->setGotoSimple("index","user");
    			$this->_redirector->redirectAndExit();
    			return;
    		}
    	}
    	$this->_redirector->setCode(303)
    			  ->setExit(false)
    			  ->setGotoSimple("index","login");
    	$this->_redirector->redirectAndExit();
    	return;
    	
    }

}

