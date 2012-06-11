<?php

class Application_Model_ClassMapper
{

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
			$this->setDbTable('Application_Model_DbTable_Class');
		}
		return $this->_dbTable;
	}
	
	public function save(Application_Model_Class $class)
	{
		$data = array(
				'title' 		=> $class->getTitle(),
				'description'	=> $class->getDescription(),
				'shared'		=> $class->getShared(),
				'created'		=> date('Y-m-d H:i:s')
		);
	
		if (null === ($id = $class->getId())) {
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
	
	public function find($id, Application_Model_Class $class)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}
		$row = $result->current();
		$class->setId($row->id)
		->setTitle($row->title)
		->setDescription($row->description)
		->setShared($row->shared)
		->setCreated($row->created);
	}
	
	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries   = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_Class();
			$classAssignments = $row->findDependentRowset('Application_Model_DbTable_Assignment');
			$entry->setId($row->id)
			->setTitle($row->title)
			->setDescription($row->description)
			->setShared($row->shared)
			->setCreated($row->created)
			->setAssignments($classAssignments);
			$entries[] = $entry;
		}
		return $entries;
	}
	
}

