<?php

class Application_Model_User
{
    protected $_username;
    protected $_password;
    protected $_first_name;
    protected $_last_name;
    protected $_email;
    protected $_created;
    protected $_id;
    
    public function __construct(array $options = null)
    {
    	if (is_array($options)) {
    		$this->setOptions($options);
    	}
    }
 
    public function __set($name, $value)
    {
//     	$temp = strtolower($name);
//     	if (property_exists($this,$temp))
//     	{
//     		$this->$temp = $value;
//     	}
//     	else
//     	{
//     		echo $name . " does not exist.";
//     	}
    	
    	$method = 'set' . $name;
    	if (('mapper' == $name) || !method_exists($this, $method)) {
    		throw new Exception('Invalid user property');
    	}
    	$this->$method($value);
    }
    
    public function __get($name)
    {
    	$method = 'get' . $name;
    	if (('mapper' == $name) || !method_exists($this, $method)) {
    		throw new Exception('Invalid user property');
    	}
    	return $this->$method();
    }
    
    public function setOptions(array $options)
    {
    	$methods = get_class_methods($this);
    	foreach ($options as $key => $value) {
    		$method = 'set' . ucfirst($key);
    		if (in_array($method, $methods)) {
    			$this->$method($value);
    		}
    	}
    	return $this;
    }
 
    public function setUsername($username)
    {
    	$this->_username = (string) $username;
    	return $this;
    }
    
    public function getUsername()
    {
    	return $this->_username;
    }
    
    public function setPassword($password)
    {
    	$this->_password = (string) $password;
    	return $this;
    }
    
    public function getPassword()
    {
    	return $this->_password;
    }
    
    public function setFirstName($first_name)
    {
    	$this->_first_name = (string) $first_name;
    	return $this;
    }
    
    public function getFirstName()
    {
    	return $this->_first_name;
    }
    
    public function setLastName($last_name)
    {
    	$this->_last_name = (string) $last_name;
    	return $this;
    }
    
    public function getLastName()
    {
    	return $this->_last_name;
    }
 
    public function setEmail($email)
    {
    	$this->_email = (string) $email;
    	return $this;
    }
    
    public function getEmail()
    {
    	return $this->_email;
    }
    
    public function setCreated($time_stamp)
    {
    	$this->_created = $time_stamp;
    	return $this;
    }
    
    public function getCreated()
    {
    	return $this->_created;
    }
 
    public function setId($id)
    {
    	$this->_id = (int) $id;
    	return $this;
    }
    
    public function getId()
    {
    	return $this->_id;
    }
}