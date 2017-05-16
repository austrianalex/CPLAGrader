<?php

class Application_Model_DbTable_Roles extends Zend_Db_Table_Abstract
{

    protected $_name = 'roles';
    protected $_dependentTables = array('Application_Model_DbTable_Users');


}

