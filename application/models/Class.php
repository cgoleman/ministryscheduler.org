<?php

class Application_Model_Class
{
    protected $_title;
    protected $_description;
    protected $_created;
    protected $_shared;
    protected $_id;
    protected $_assignments;
    
    public function __construct(array $options = null)
    {
    	if (is_array($options)) {
    		$this->setOptions($options);
    	}
    }
 
    public function __set($name, $value)
    {
    	$method = 'set' . $name;
    	if (('mapper' == $name) || !method_exists($this, $method)) {
    		throw new Exception('Invalid class property');
    	}
    	$this->$method($value);
    }
    
    public function __get($name)
    {
    	$method = 'get' . $name;
    	if (('mapper' == $name) || !method_exists($this, $method)) {
    		throw new Exception('Invalid class property');
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

    public function setAssignments($assignments)
    {
    	$this->_assignments = $assignments;
    	return $this;
    }
    
    public function getAssignments()
    {
    	return $this->_assignments;
    }
    
    public function setShared($shared)
    {
    	if ($shared == 1) {
    		$this->_shared = "Yes";
    	} else {
    		$this->_shared = "No";
    	}
    	return $this;
    }
    
    public function getShared()
    {
    	if (strtolower($this->_shared) == "yes") {
    		return 1;
    	} else {
    		return 0;
    	}
    }
    
    public function setDescription($description)
    {
    	$this->_description = (string) $description;
    	return $this;
    }
    
    public function getDescription()
    {
    	return $this->_description;
    }
 
    public function setTitle($title)
    {
    	$this->_title = (string) $title;
    	return $this;
    }
    
    public function getTitle()
    {
    	return $this->_title;
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