<?php

class Application_Model_DbTable_Assignment extends Zend_Db_Table_Abstract
{

    protected $_name = 'assignment';

    protected $_referenceMap    = array(
    		'Class' => array(
    				'columns'           => 'class_id',
    				'refTableClass'     => 'Application_Model_DbTable_Class',
    				'refColumns'        => 'id'
    		));

}

