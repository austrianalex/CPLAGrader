<?php

class Application_Model_DbTable_AssignmentTypes extends Zend_Db_Table_Abstract
{

    protected $_name = 'assignment_types';
    protected $_dependentTables = array('Application_Model_DbTable_Assignments');

}

