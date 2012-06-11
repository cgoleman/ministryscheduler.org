<?php

class Application_Form_User extends Zend_Form
{
	public function init()
	{
		// Set the method for the display form to POST
        $this->setMethod('post');
 
        // Add the first_name element
        $this->addElement('text', 'FirstName', array(
            'label'      => 'First Name:',
            'required'   => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 120))
                )
        ));
        
        // Add the last_name element
        $this->addElement('text', 'LastName', array(
        		'label'      => 'Last Name:',
        		'required'   => true,
        		'validators' => array(
        				array('validator' => 'StringLength', 'options' => array(0, 120))
        		)
        ));
        
        // Add the username element
        $this->addElement('text', 'Username', array(
        		'label'      => 'Username:',
        		'required'   => true,
        		'validators' => array(
        				array('validator' => 'StringLength', 'options' => array(0, 120))
        		)
        ));
        
        // Add the password element
        $this->addElement('password', 'Password', array(
        		'label'      => 'Password:',
        		'required'   => true,
        		'validators' => array(
        				array('validator' => 'StringLength', 'options' => array(0, 120))
        		)
        ));
        
        
        // Add an email element
        $this->addElement('text', 'Email', array(
        		'label'      => 'Email address:',
        		'required'   => true,
        		'filters'    => array('StringTrim'),
        		'validators' => array(
        				'EmailAddress',
        		)
        ));
 
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Save',
        ));
 
        // And finally add some CSRF protection
//         $this->addElement('hash', 'csrf', array(
//             'ignore' => true,
//         ));
    }
}

