<?php

class Application_Model_AssignmentMapper
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
			$this->setDbTable('Application_Model_DbTable_Assignment');
		}
		return $this->_dbTable;
	}
	
	public function save(Application_Model_Assignment $assignment)
	{
		$data = array(
				'title' 		=> $assignment->getTitle(),
				'description'	=> $assignment->getDescription(),
				'shared'		=> $assignment->getShared(),
				'start_date'	=> $assignment->getStartDate(),
				'end_date'		=> $assignment->getEndDate(),
				'class_id'		=> $assignment->getClassId(),
				'user_id'		=> $assignment->getUserId(),
				'created'		=> $assignment->getCreated()
		);
	
		if (null === ($id = $assignment->getId())) {
			unset($data['id']);
			$data['created'] = date('Y-m-d H:i:s');
			
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
	
	public function find($id, Application_Model_Assignment $assignment)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}
		$row = $result->current();
		$assignment->setId($row->id)
		->setTitle($row->title)
		->setDescription($row->description)
		->setShared($row->shared)
		->setStartDate($row->start_date)
		->setEndDate($row->end_date)
		->setClassId($row->class_id)
		->setUserId($row->user_id)
		->setCreated($row->created);
	}
	
	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries   = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_Assignment();
			$entry->setId($row->id)
			->setTitle($row->title)
			->setDescription($row->description)
			->setShared($row->shared)
			->setStartDate($row->start_date)
			->setEndDate($row->end_date)
			->setClassId($row->class_id)
			->setUserId($row->user_id)
			->setCreated($row->created);
			$entries[] = $entry;
		}
		return $entries;
	}
	
}

