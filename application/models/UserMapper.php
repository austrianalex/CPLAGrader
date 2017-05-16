<?php

class Application_Model_UserMapper
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
            $this->setDbTable('Application_Model_DbTable_Users');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_User $user)
    {
        $data = array(
            'id'      => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'role_id' => $user->getRoleId()
        );
        if (null !== $user->getPassword())
            $data['password'] = $user->getPassword();
        if (null === ($id = $user->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
        
    }
    
    public function find($id, Application_Model_User $user)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        
        $ut = new Application_Model_UserTests();
        $utmapper = new Application_Model_UserTestsMapper();
        $utmapper->find($row->id, $ut);
        
        $user->setId($row->id)
                  ->setEmail($row->email)
                  ->setUsername($row->username)
                  ->setFirstName($row->first_name)
                  ->setLastName($row->last_name)
                  ->setRoleId($row->role_id)
                  ->setModelTest($ut);
    }
    
    public function usernameAvailable($username)
    {
        $select = $this->getDbTable()->select();
        $select->where('username=?',$username);
        $result = $this->getDbTable()->fetchAll($select);
        if (0 == count($result))
            return true;
        return false;
    }
 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $users   = array();
        foreach ($resultSet as $row) {
            $ut = new Application_Model_UserTests();
            $utmapper = new Application_Model_UserTestsMapper();
            $utmapper->find($row->id, $ut);
            $user = new Application_Model_User();
            $user->setId($row->id)
                  ->setEmail($row->email)
                  ->setUsername($row->username)
                  ->setFirstName($row->first_name)
                  ->setLastName($row->last_name)
                  ->setRoleId($row->role_id)
                  ->setModelTest($ut);
            $users[] = $user;
        }
        uasort($users,'Application_Model_User::compareTo');
        return $users;
    }

}

