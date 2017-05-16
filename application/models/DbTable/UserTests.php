<?php

class Application_Model_DbTable_UserTests extends Zend_Db_Table_Abstract
{

    protected $_name = 'user_tests';
    protected $_referenceMap = array(
        'User' => array(
            'columns' => 'user_id',
            'refTableClass' => 'Application_Model_DbTable_Users',
            'refColumns' => 'id'
        )
    );


}

