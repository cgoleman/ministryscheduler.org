<?php

class Application_Model_UserMapper
{
	const WRONG_PASSWORD = 1;
	const NOT_FOUND = 2;
	
	protected $_dbTable;
	
	public function setDbTable($dbTable)
	{
		if (is_string($dbTable)) {
			$dbTable = new $dbTable();
		}
		if (!$dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}
	
	public function getDbTable()
	{
		if (null === $this->_dbTable) {
			$this->setDbTable('Application_Model_DbTable_User');
		}
		return $this->_dbTable;
	}
	
	public function save(Application_Model_User $user)
	{
		$data = array(
				'email' 		=> $user->getEmail(),
				'username' 		=> $user->getUsername(),
				'password' 		=> $user->getPassword(),
				'first_name' 	=> $user->getFirstName(),
				'last_name'		=> $user->getLastName(),
				'created'		=> date('Y-m-d H:i:s')
		);
	
		if (null === ($id = $user->getId())) {
			unset($data['id']);
			$this->getDbTable()->insert($data);
		} else {
			$this->getDbTable()->update($data, array('id = ?' => $id));
		}
	}
	
	public function delete($id)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}
		$this->getDbTable()->delete(array("id = ?" => $id));
	}
	
	public function find($id, Application_Model_User $user)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}
		$row = $result->current();
		$user->setId($row->id)
		->setEmail($row->email)
		->setUsername($row->username)
		->setPassword($row->password)
		->setFirstName($row->first_name)
		->setLastName($row->last_name)
		->setCreated($row->created);
	}
	
	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries   = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_User();
			$entry->setId($row->id)
			->setEmail($row->email)
			->setUsername($row->username)
			->setPassword($row->password)
			->setFirstName($row->first_name)
			->setLastName($row->last_name)
			->setCreated($row->created);
			$entries[] = $entry;
		}
		return $entries;
	}
	
	/*
	 * 
	 * @param string $username
	 * @param string $password
	 * @throws Exception
	 * @returns Application_Model_User
	 */
	public static function authenticate($username, $password)
	{
		$user = new Application_Model_UserMapper();
		
		$select = $user->getDbTable()->select()->where('username = ?', $username);
		
		$row = $user->getDbTable()->fetchRow($select);
		
		if ($row) {
			if ($row->password == $password)
				return Application_Model_UserMapper::loadUser($row);
			throw new Exception(self::WRONG_PASSWORD);
		}
		throw new Exception(self::NOT_FOUND);
	
	}
	
	private static function loadUser($data)
	{
		$user = new Application_Model_User();
		$user->setId($data->id)
		->setEmail($data->email)
		->setUsername($data->username)
		->setPassword($data->password)
		->setFirstName($data->first_name)
		->setLastName($data->last_name)
		->setCreated($data->created);
	
		return $user;
	}

}

