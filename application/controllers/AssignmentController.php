<?php

class AssignmentController extends Zend_Controller_Action
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
        $assignment = new Application_Model_AssignmentMapper();
        $this->view->entries = $assignment->fetchAll();
    }

    public function addAction()
    {
    	$request = $this->getRequest();
    	$form    = new Application_Form_Assignment();
    
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			$assignment = new Application_Model_Assignment($form->getValues());
    			$assignment->setUserId(Zend_Registry::get('userId'));
    			$mapper  = new Application_Model_AssignmentMapper();
    			$mapper->save($assignment);
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
    			$assignment = new Application_Model_Assignment();
    			$mapper  = new Application_Model_AssignmentMapper();
    			$mapper->delete($id);
    		}
    		$this->_helper->redirector('index');
    	}
    	else
    	{
    		$id = $this->getRequest()->getParam('id');
    		$assignment = new Application_Model_Assignment();
    		$mapper  = new Application_Model_AssignmentMapper();
    		$mapper->find($id, $assignment);
    		$this->view->assignment = $assignment;
    	}
    }
    
    public function editAction()
    {
    	$request = $this->getRequest();
    	$form    = new Application_Form_Assignment();
    
    	if ($this->getRequest()->isPost()) {
    		$formData = $this->getRequest()->getPost();
    		if ($form->isValid($formData)) {
    			$assignment = new Application_Model_Assignment($form->getValues());
    			$assignment->setId($this->getRequest()->getParam('id'));
    			$mapper  = new Application_Model_AssignmentMapper();
    			$mapper->save($assignment);
    			return $this->_helper->redirector('index');
    		}
    		else {
    			$form->populate($formData);
    		}
    	}
    	 
    
    	$id = $this->getRequest()->getParam('id');
    	$assignment = new Application_Model_Assignment();
    	$mapper  = new Application_Model_AssignmentMapper();
    	$mapper->find($id, $assignment);
    	
    	$startDate = new Zend_Date();
    	$startDate->set($assignment->getStartDate(), Zend_Date::ISO_8601);
    	$endDate = new Zend_Date();
    	$endDate->set($assignment->getEndDate(), Zend_Date::ISO_8601);
    	
    	$assignmentMap = array (
    						'Title' => $assignment->getTitle(),
    						'Description' => $assignment->getDescription(),
    						'Shared' => $assignment->getShared(),
    						'StartDate'	=> $startDate->getIso(),
    						'EndDate' => $endDate->getIso()
    					);
    	 
    	$form->populate($assignmentMap);
    	 
    	$this->view->form = $form;
    }
    
    public function shareAction()
    {
    	$request = $this->getRequest();

    	$id = $this->getRequest()->getParam('id');
    	$assignment = new Application_Model_Assignment();
    	$mapper  = new Application_Model_AssignmentMapper();
    	
    	$mapper->find($id, $assignment);
       	$assignment->setShared(1);
    	$mapper->save($assignment);
    	
    	$this->_helper->redirector('index');
    }
    
    public function unshareAction()
    {
    	$request = $this->getRequest();
    
    	$id = $this->getRequest()->getParam('id');
    	$assignment = new Application_Model_Assignment();
    	$mapper  = new Application_Model_AssignmentMapper();
    	 
    	$mapper->find($id, $assignment);
    	$assignment->setShared(0);
    	$mapper->save($assignment);
    	 
    	$this->_helper->redirector('index');
    }
    
    public function assignAction()
    {
    	//Todo: Implement
    	throw new Exception("Not Implemented");
    }
}

