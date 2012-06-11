<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initPlaceholders()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
        
        $view->headTitle('Ministry Scheduler')
        	 ->setSeparator(' :: ');
    }
    
    public function _initViewHelpers()
    {
    	$this->bootstrap('layout');
    	$this->_layout = $this->getResource('layout');
    	$this->_view = $this->_layout->getView();
    	
    	$this->_view->addHelperPath('Zend/Dojo/View/Helper','Zend_Dojo_View_Helper');
    	
    	$this->_view->dojo()
    	->enable()
    	->setCdnBase(Zend_Dojo::CDN_BASE_GOOGLE)
    	->setCdnVersion('1.5.0')
    	->setCdnDojoPath(Zend_Dojo::CDN_DOJO_PATH_GOOGLE)
    	->addStyleSheetModule('dijit.themes.soria')
    	->useCdn();
    	}
}

