<?php

class ClassController extends Zend_Controller_Action
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
        $class = new Application_Model_ClassMapper();
        $this->view->entries = $class->fetchAll();
    }

    public function addAction()
    {
    	$request = $this->getRequest();
    	$form    = new Application_Form_Class();
    
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			$class = new Application_Model_Class($form->getValues());
    			$mapper  = new Application_Model_ClassMapper();
    			$mapper->save($class);
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
    			$class = new Application_Model_Class();
    			$mapper  = new Application_Model_ClassMapper();
    			$mapper->delete($id);
    		}
    		$this->_helper->redirector('index');
    	}
    	else
    	{
    		$id = $this->getRequest()->getParam('id');
    		$class = new Application_Model_Class();
    		$mapper  = new Application_Model_ClassMapper();
    		$mapper->find($id, $class);
    		$this->view->class = $class;
    	}
    }
    
    public function editAction()
    {
    	$request = $this->getRequest();
    	$form    = new Application_Form_Class();
    
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			$class = new Application_Model_Class($form->getValues());
    			$class->setId($this->getRequest()->getParam('id'));
    			$mapper  = new Application_Model_ClassMapper();
    			$mapper->save($class);
    			return $this->_helper->redirector('index');
    		}
    		else {
    			$form->populate($formData);
    		}
    	}
    	 
    	$id = $this->getRequest()->getParam('id');
    	$class = new Application_Model_Class();
    	$mapper  = new Application_Model_ClassMapper();
    	$mapper->find($id, $class);
    	 
    	$classMap = array ( 'Title' 		=> $class->getTitle(),
    						'Description' 	=> $class->getDescription(),
    						'Shared'		=> $class->getShared()
    	);
    	 
    	$form->populate($classMap);
    	 
    	$this->view->form = $form;
    }
    
    public function shareAction()
    {
    	$request = $this->getRequest();

    	$id = $this->getRequest()->getParam('id');
    	$class = new Application_Model_Class();
    	$mapper  = new Application_Model_ClassMapper();
    	
    	$mapper->find($id, $class);
       	$class->setShared(1);
    	$mapper->save($class);
    	
    	$this->_helper->redirector('index');
    }
    
    public function unshareAction()
    {
    	$request = $this->getRequest();
    
    	$id = $this->getRequest()->getParam('id');
    	$class = new Application_Model_Class();
    	$mapper  = new Application_Model_ClassMapper();
    	 
    	$mapper->find($id, $class);
    	$class->setShared(0);
    	$mapper->save($class);
    	 
    	$this->_helper->redirector('index');
    }
    
    public function assignAction()
    {
    	//Todo: Implement
    	throw new Exception("Not Implemented");
    }
}

