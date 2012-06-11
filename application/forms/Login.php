<?php

class Application_Form_Login extends Zend_Form
{

	public $elementDecorators = array(
			'ViewHelper',
			'Errors',
			array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
			array('Label', array('tag' => 'td')),
			array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
	);
	
	public $buttonDecorators = array(
			'ViewHelper',
			array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
			array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
			array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
	);
	
	public function loadDefaultDecorators()
	{
		$this->setDecorators(array(
				'FormElements',
				array('HtmlTag', array('tag' => 'table')),
				'Form',
		));
	}
	
    public function init()
    {
    	// Set the method for the display form to POST
        $this->setMethod('post');
        $this->setOptions(array('class' => 'loginForm'));
 
        // Add the username element
        $this->addElement('text', 'Username', array(
        		'decorators' => $this->elementDecorators,
        		'label'      => 'Username:',
        		'required'   => true,
        		'validators' => array(
        				array('validator' => 'StringLength', 'options' => array(0, 120))
        		)
        ));
        
        // Add the password element
        $this->addElement('password', 'Password', array(
        		'decorators' => $this->elementDecorators,
        		'label'      => 'Password:',
        		'required'   => true,
        		'validators' => array(
        				array('validator' => 'StringLength', 'options' => array(0, 120))
        		)
        ));
 
        // Add the submit button
        $this->addElement('submit', 'login', array(
        	'decorators' => $this->buttonDecorators,
            'ignore'   => true,
            'label'    => 'Login',
        ));
        
        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
        		'ignore' => true,
        		'hidden' => true
        ));
    }


}

