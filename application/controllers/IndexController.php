<?php
class IndexController extends Zend_Controller_Action {
	
	protected $_redirector = null;
	
	public function init() {
		$this->_redirector = $this->_helper->getHelper('Redirector');
	}
	public function indexAction() {
		$this->_redirector->setCode ( 303 )->setExit ( false )->setGotoSimple ( "index", "login" );
		$this->_redirector->redirectAndExit ();
		return;
	}
}

