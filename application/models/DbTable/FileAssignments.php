<?php

class Application_Model_DbTable_FileAssignments extends Zend_Db_Table_Abstract
{

    protected $_name = 'file_assignments';
    protected $_referenceMap = array(
        'Grade' => array(
            'columns' => 'grade_id',
            'refTableClass' => 'Application_Model_DbTable_UsersAssignments',
            'refColumns' => 'id'
        )
    );

}

