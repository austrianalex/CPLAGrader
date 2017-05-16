<?php

class Application_Model_DbTable_UsersAssignments extends Zend_Db_Table_Abstract
{

    protected $_name = 'users_assignments';
    protected $_dependentTables = array('Application_Model_DbTable_HtmlAssignments');
    
    protected $_referenceMap = array(
        'Assignment' => array(
            'columns' => 'assignment_id',
            'refTableClass' => 'Application_Model_DbTable_Assignments',
            'refColumns' => 'id'
        ),
        
        'User' => array(
            'columns' => 'user_id',
            'refTableClass' => 'Application_Model_DbTable_Users',
            'refColumns' => 'id'
        )
    
    );


}

