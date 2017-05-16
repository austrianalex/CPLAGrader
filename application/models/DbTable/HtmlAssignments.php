<?php

class Application_Model_DbTable_HtmlAssignments extends Zend_Db_Table_Abstract
{

    protected $_name = 'html_assignments';
    protected $_referenceMap = array(
        'Grade' => array(
            'columns' => 'grade_id',
            'refTableClass' => 'Application_Model_DbTable_UsersAssignments',
            'refColumns' => 'id'
        )
    );

}

