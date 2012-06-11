<?php
class Application_Form_Class extends Zend_Dojo_Form {
	protected $_buttons = array ();
	public $elementDecorators = array (
			'ViewHelper',
			'Errors',
			array (	array ('data' => 'HtmlTag'),
					array ('tag' => 'td','class' => 'element')),
			array ('Label',	array ('tag' => 'td')),
			array (	array ('row' => 'HtmlTag'),
					array ('tag' => 'tr')) 
	);
	public $buttonDecorators = array (
			'ViewHelper',
			array ( array ('data' => 'HtmlTag'),
					array ('tag' => 'td','class' => 'element')),
			array (	array ('label' => 'HtmlTag'),
					array ('tag' => 'td','placement' => 'prepend')),
			array (	array ('row' => 'HtmlTag'),
					array ('tag' => 'tr')) 
	);
	public function loadDefaultDecorators() {
		$this->setDecorators ( array ('FormElements',array ('HtmlTag',array ('tag' => 'table')),'Form'));
	}
	
	public function init() {
		// Set the method for the display form to POST
		$this->setMethod ( 'post' );
		// $this->setOptions(array('class' => 'loginForm'));
		
		// Add the title element
		$this->addElement ( 'ValidationTextBox', 'Title', array (
				'label' => 'Class Title:',
				'missingMessage' => 'Required',
				'promptMessage' => 'Enter class title',
				'invalidMessage' => 'Class title must be less than 120 characters',
				'regExp' => '.{1,120}',
				'required' => true,
				'validators' => array (
						array (
								'validator' => 'StringLength',
								'options' => array (
										1,
										120 
								) 
						) 
				),
				'filters' => array (
						array (
								'filter' => 'StringTrim',
								'filter' => 'StripTags' 
						) 
				) 
		) );
		
		// Add the description element
		$this->addElement ( 'textarea', 'Description', array (
				'label' => 'Class Description:',
				'promptMessage' => 'Enter a description for this class.',
				'style' => 'min-height: 100px;',
				'required' => false 
		) );
		
		// Add the shared checkbox
		$this->addElement ( 'checkbox', 'Shared', array (
				'label' => 'Share:',
				'checkedValue' => 1,
				'uncheckedValue' => 0
		) );
		
// 		$subForm2 = new Zend_Dojo_Form_SubForm();
// 		$subForm2->setAttribs(array(
// 				'name' => 'searchtab',
// 				'legend' => 'Search',
// 				'dijitParams' => array(
// 						'title' => 'Find Classes')
// 		)
// 		);
		
// 		$this->addSubForm($subForm1, 'classlisttab')
// 			 ->addSubForm($subForm2, 'searchtab');
		
		$this->setButtons ( array (
				'submit' => 'Save',
				'reset' => 'Cancel' 
		) );
	}
	public function setButtons($buttons) {
		$this->_buttons = $buttons;
		foreach ( $buttons as $name => $label ) {
			$this->addElement ( $name, $name, array (
					'label' => $label,
					'class' => $name,
					'decorators' => array ('ViewHelper') 
			) );
		}
		$this->addDisplayGroup ( array_keys ( $this->_buttons ), 'buttons', array (
				'decorators' => array ( 'FormElements',	
										array ('HtmlTag', array ('tag' => 'div','class' => 'buttons')),
										'DtDdWrapper')
				)
			);
	}
}

