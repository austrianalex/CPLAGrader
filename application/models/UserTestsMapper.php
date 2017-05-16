<?php

class Application_Model_UserTestsMapper
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
            $this->setDbTable('Application_Model_DbTable_UserTests');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_UserTests $ut)
    {
        $data = array(
            'user_id' => $ut->getUserId(),
            'test1_grade' => $ut->getTest1(),
            'test2_grade' => $ut->getTest2(),
            'test1_retake' => chr($ut->getTestRetake1()),
            'test2_retake' => chr($ut->getTestRetake2())
        );
        $this->getDbTable()->update($data, array('user_id = ?' => $data['user_id']));

    }
    
    public function find($id, Application_Model_UserTests $ut)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            $this->getDbTable()->insert(
                array('user_id'=>$id,
            			'test1_grade'=>0,
            			'test2_grade'=>0
            ));
           
            $result = $this->getDbTable()->find($id);
        }
        $row = $result->current();
        $ut->setUserId($row->user_id)
                  ->setTest1($row->test1_grade)
                  ->setTest2($row->test2_grade)
                  ->setTestRetake1(ord($row->test1_retake) == 1)
                  ->setTestRetake2(ord($row->test2_retake) == 1);
    }
     /*
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $users   = array();
        foreach ($resultSet as $row) {
            $user = new Application_Model_User();
            $user->setId($row->id)
                  ->setEmail($row->email)
                  ->setComment($row->comment)
                  ->setCreated($row->created);
            $users[] = $user;
        }
        return $users;
    }*/

}

