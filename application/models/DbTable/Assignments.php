<?php

class Application_Model_DbTable_Assignments extends Zend_Db_Table_Abstract
{

    protected $_name = 'assignments';
    protected $_dependentTables = array('Application_Model_DbTable_UsersAssignments');
    protected $_referenceMap = array(
        'Type' => array(
            'columns' => 'type_id',
            'refTableClass' => 'Application_Model_DbTable_AssignmentTypes',
            'refColumns' => 'id'
        )
    
    );

}

