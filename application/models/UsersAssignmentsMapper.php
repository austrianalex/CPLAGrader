<?php

class Application_Model_UsersAssignmentsMapper
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
            $this->setDbTable('Application_Model_DbTable_UsersAssignments');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_UsersAssignments $ua)
    {
        $data = array(
            'user_id' => $ua->getUserId(),
            'assignment_id' => $ua->getAssignmentId(),
            'score' => $ua->getScore(),
            'graded' => $ua->getGraded(),
            'submit_attempt' => $ua->getSubmitAttempt(),
            'comment' => $ua->getComment(),
        );
        if (null === $data['comment'])
        {
            unset($data['comment']);
        }
        if (null === $data['score'])
        {
            unset($data['score']);
        }
        if (null === $data['submit_attempt'])
        {
            unset($data['submit_attempt']);
        }
        $id = 0;
        if (null === ($id = $ua->getId())) {
            unset($data['id']);
            //check if record exists with same user_id and assignment
            $select = $this->getDbTable()->select();
            $select->where('user_id = ?',$data['user_id'])
                   ->where('assignment_id = ?', $data['assignment_id']);
            if (!$this->getDbTable()->fetchRow($select))
                $id = $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
        if (null !== $ua->getUrl())
        {
            //Painful way to do it, but better/faster than making a new mapper
            $dbTable = new Application_Model_DbTable_HtmlAssignments();
            $sql = 'INSERT INTO html_assignments (grade_id, url) VALUES(?,?) 
            		ON DUPLICATE KEY UPDATE grade_id = ?, url = ?';
            $values = array('grade_id'=>$id,'url'=>$ua->getUrl());
            $dbTable->getAdapter()->query($sql, array_merge(array_values($values), array_values($values)));
        }
        if (null !== $ua->getFile())
        {
            //Painful way to do it, but better/faster than making a new mapper
            $dbTable = new Application_Model_DbTable_FileAssignments();
            $sql = 'INSERT INTO file_assignments (grade_id, file, filename) VALUES(?,?,?) 
            		ON DUPLICATE KEY UPDATE grade_id = ?, file = ?, filename = ?';
            $values = array('grade_id'=>$id,'file'=>$ua->getFile(), 'filename' => $ua->getFilename());
            $dbTable->getAdapter()->query($sql, array_merge(array_values($values), array_values($values)));
        }
        
    }

    public function find($id, Application_Model_UsersAssignments $ua)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            $ua->setId(null);
            return;
        }
        $row = $result->current();
        $assignment = $row->findParentRow('Application_Model_DbTable_Assignments');
        $assignment_model = new Application_Model_Assignment($assignment->toArray());
        $user = $row->findParentRow('Application_Model_DbTable_Users');
        $user_model = new Application_Model_User($user->toArray());
        //load html by default if it exists, but save files for a explicit call-only basis
        $html = $row->findDependentRowset('Application_Model_DbTable_HtmlAssignments');
        $html = $html->current();
        
        $ua->setId($row->id)
                  ->setUserId($row->user_id)
                  ->setAssignmentId($row->assignment_id)
                  ->setScore($row->score)
                  ->setComment($row->comment)
                  ->setGraded(ord($row->graded) == 1)
                  ->setModelAssignment($assignment_model)
                  ->setModelUser($user_model);
        if ($html != null)
                  $ua->setUrl($html->url);
    }

    public function findUserAssignment($user_id, $assignment_id, Application_Model_UsersAssignments $ua)
    {
        $select = $this->getDbTable()->select()->where('user_id=?',$user_id)
                                               ->where('assignment_id=?',$assignment_id);
        $result = $this->getDbTable()->fetchAll($select);
        if (0 == count($result)) {
            $ua->setId(null);
            return;
        }
        $row = $result->current();
        
        /*$assignment = $row->findParentRow('Application_Model_DbTable_Assignments');
        $assignment_model = new Application_Model_Assignment($assignment);
        $user = $row->findParentRow('Application_Model_DbTable_Users');
        $user_model = new Application_Model_User($user);*/
        $html = $row->findDependentRowset('Application_Model_DbTable_HtmlAssignments');
        $html = $html->current();
        
        $ua->setUserId($row->user_id)
              ->setAssignmentId($row->assignment_id)
              ->setScore($row->score)
              ->setGraded(ord($row->graded) == 1)
              ->setId($row->id)
              ->setComment($row->comment)
              ->setSubmitAttempt($row->submit_attempt);
              //->setModelAssignment($assignment_model)
              //->setModelUser($user_model);
        if ($html != null)
            $ua->setUrl($html->url);
    }
    
    public function fetchByAssignment($assignment_id)
    {
        $select = $this->getDbTable()->select()->where('assignment_id=?',$assignment_id);
        $resultSet = $this->getDbTable()->fetchAll($select);
        $uas   = array();
        foreach ($resultSet as $row) {
            $assignment = $row->findParentRow('Application_Model_DbTable_Assignments');
            $assignment_model = new Application_Model_Assignment($assignment->toArray());
            $user = $row->findParentRow('Application_Model_DbTable_Users');
            $user_model = new Application_Model_User($user->toArray());
            $html = $row->findDependentRowset('Application_Model_DbTable_HtmlAssignments');
            $html = $html->current();
            
            $ua = new Application_Model_UsersAssignments();
            $ua->setId($row->id)
                  ->setUserId($row->user_id)
                  ->setAssignmentId($row->assignment_id)
                  ->setScore($row->score)
                  ->setGraded(ord($row->graded) == 1)
                  ->setComment($row->comment)
                  ->setModelAssignment($assignment_model)
                  ->setModelUser($user_model)
                  ->setSubmitAttempt($row->submit_attempt);
            if ($html != null)
                  $ua->setUrl($html->url);
            $uas[] = $ua;
        }
        uasort($uas,'Application_Model_UsersAssignments::compareTo');
        return $uas;
    }
    
    public function fetchByUser($user_id)
    {
        $select = $this->getDbTable()->select()->where('user_id=?',$user_id);
        $resultSet = $this->getDbTable()->fetchAll($select);
        $uas   = array();
        foreach ($resultSet as $row) {
            $assignment = $row->findParentRow('Application_Model_DbTable_Assignments');
            $assignment_model = new Application_Model_Assignment($assignment->toArray());
            $user = $row->findParentRow('Application_Model_DbTable_Users');
            $user_model = new Application_Model_User($user->toArray());
            $html = $row->findDependentRowset('Application_Model_DbTable_HtmlAssignments');
            $html = $html->current();
            
            $ua = new Application_Model_UsersAssignments();
            $ua->setId($row->id)
                  ->setUserId($row->user_id)
                  ->setAssignmentId($row->assignment_id)
                  ->setScore($row->score)
                  ->setGraded(ord($row->graded) == 1)
                  ->setComment($row->comment)
                  ->setModelAssignment($assignment_model)
                  ->setModelUser($user_model)
                  ->setSubmitAttempt($row->submit_attempt);
            if ($html != null)
                  $ua->setUrl($html->url);
            $uas[] = $ua;
        }
        return $uas;
    }
    
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $uas   = array();
        foreach ($resultSet as $row) {
            $assignment = $row->findParentRow('Application_Model_DbTable_Assignments');
            $assignment_model = new Application_Model_Assignment($assignment->toArray());
            $user = $row->findParentRow('Application_Model_DbTable_Users');
            $user_model = new Application_Model_User($user->toArray());
            $html = $row->findDependentRowset('Application_Model_DbTable_HtmlAssignments');
            $html = $html->current();
            
            $ua = new Application_Model_UsersAssignments();
            $ua->setId($row->id)
                  ->setUserId($row->user_id)
                  ->setAssignmentId($row->assignment_id)
                  ->setScore($row->score)
                  ->setGraded(ord($row->graded) == 1)
                  ->setComment($row->comment)
                  ->setSubmitAttempt($row->submit_attempt)
                  ->setModelAssignment($assignment_model)
                  ->setModelUser($user_model);
            if ($html != null)
                  $ua->setUrl($html->url);
            $uas[] = $ua;
        }
        return $uas;
    }
    
    public function loadFileAssignment(Application_Model_UsersAssignments $ua)
    {
        $db = new Application_Model_DbTable_FileAssignments();
        $result = $db->find($ua->getId());
        $row = $result->current();
        if ($row == null)
            return false;
        $ua->setFile($row->file);
        $ua->setFilename($row->filename);
        return true;
    }

}

