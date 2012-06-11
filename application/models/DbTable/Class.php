<?php

class Application_Model_DbTable_Class extends Zend_Db_Table_Abstract
{

    protected $_name = 'class';
    protected $_dependentTables = array('Application_Model_DbTable_Assignment');

}

