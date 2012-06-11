<?php

class BL_Auth_Adapter implements Zend_Auth_Adapter_Interface {

	const NOT_FOUND_MSG = "Account not found";
	
	const WRONG_PASSWORD_MSG = "Invalid password";
	
	/**
	 * 
	 * @var Application_Model_User
	 */
	protected $user;
	
	/**
	 * 
	 * @var string
	 */
	protected $username = "";
	
	/**
	 * 
	 * @var string
	 */
	protected $password = "";
	
	function __construct( $username, $password) {
		
		$this->username = $username;
		
		$this->password = $password;
	
	}
	
	/**
	 * @see Zend_Auth_Adapter_Interface::authenticate()
	 * 
	 * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
	 */
	public function authenticate() {
		// @todo implement exception handling for db errors
		
		try {
			$this->user = Application_Model_UserMapper::authenticate($this->username, $this->password);
			Zend_Registry::set('userId', $this->user->getId());
			Zend_Registry::set('userName', $this->user->getUsername());
			Zend_Registry::set('firstName', $this->user->getFirstName());
			Zend_Registry::set('lastName', $this->user->getLastName());
			
			return $this->result(Zend_Auth_Result::SUCCESS);
		} catch (Exception $e) {
			if($e->getMessage() == Application_Model_UserMapper::WRONG_PASSWORD)
				return $this->result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, array(self::WRONG_PASSWORD_MSG));
				
			if($e->getMessage() == Application_Model_UserMapper::NOT_FOUND)
				return $this->result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, array(self::NOT_FOUND_MSG));
		}
	}
	
	private function result($code, $messages = array()) {
		if (!is_array($messages)) {
			$messages = array($messages);
		}
		
		return new Zend_Auth_Result($code, $this->user, $messages);
	}
}