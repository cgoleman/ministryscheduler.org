<?php

class UserController extends Zend_Controller_Action
{

    protected $_redirector = null;
    
    protected $_auth = null;
 
    public function init()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');

        $this->view->loggedIn = 0;
        
        $this->_auth = Zend_Auth::getInstance();
        if (!$this->_auth->hasIdentity()) {
        	$this->_redirector->setCode(303)
        	->setExit(false)
        	->setGotoSimple("index",
        			"login");
        	$this->_redirector->redirectAndExit();
        	return;
        } else {
        	$this->view->loggedIn = 1;
        }
    }

    public function indexAction()
    {
        $user = new Application_Model_UserMapper();
        $this->view->entries = $user->fetchAll();
    }
    
    public function addAction()
    {
    	$request = $this->getRequest();
    	$form    = new Application_Form_User();
    
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			$user = new Application_Model_User($form->getValues());
    			$mapper  = new Application_Model_UserMapper();
    			$mapper->save($user);
    			return $this->_helper->redirector('index');
    		}
    		else {
    			$form->populate($formData);
    		}
    	}
    
    	$this->view->form = $form;
    }
    
    public function deleteAction()
    {
    	if($this->getRequest()->isPost())
    	{
    		$delete = $this->getRequest()->getPost('delete');
    		if($delete == 'Yes')
    		{
    			$id = $this->getRequest()->getParam('id');
    			if(!$id || $id == "")
    			{
    				$this->_helper->redirector('index');
    			}
    			$user = new Application_Model_User();
    			$mapper  = new Application_Model_UserMapper();
    			$mapper->delete($id);
    		}
    		$this->_helper->redirector('index');
    	}
    	else
    	{
    		$id = $this->getRequest()->getParam('id');
    		$user = new Application_Model_User();
    		$mapper  = new Application_Model_UserMapper();
    		$mapper->find($id, $user);
    		$this->view->user = $user;
    	}
    }
    
    public function editAction()
    {
    	$request = $this->getRequest();
    	$form    = new Application_Form_User();
    
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			$user = new Application_Model_User($form->getValues());
    			$user->setId($this->getRequest()->getParam('id'));
    			$mapper  = new Application_Model_UserMapper();
    			$mapper->save($user);
    			return $this->_helper->redirector('index');
    		}
    		else {
    			$form->populate($formData);
    		}
    	}
    	

    	$id = $this->getRequest()->getParam('id');
    	$user = new Application_Model_User();
    	$mapper  = new Application_Model_UserMapper();
    	$mapper->find($id, $user);
    	
    	$userMap = array ('FirstName' 		=> $user->getFirstName(),
						  'LastName' 		=> $user->getLastName(),
    					  'Username' 		=> $user->getUsername(),
    					  'Password' 		=> $user->getPassword(),
		      			  'Email' 			=> $user->getEmail()
    					  );
    	
    	$form->populate($userMap);
    	
    	$this->view->form = $form;
    }

}

