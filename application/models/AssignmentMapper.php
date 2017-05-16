<?php

class Application_Model_AssignmentMapper
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
            $this->setDbTable('Application_Model_DbTable_Assignments');
        }
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Assignment $assignment)
    {
        $data = array(
            'name' => $assignment->getName(),
            'description' => $assignment->getDescription(),
            'max_points' => $assignment->getMaxPoints(),
            'due' => $assignment->getDue(),
            'type_id' => $assignment->getTypeId(),
            'released' => chr($assignment->getReleased())
        );
        
        if (null === ($id = $assignment->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
        
    }
    
    public function find($id, Application_Model_Assignment $assignment)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            $assignment->setId(null);
            return;
        }
        $row = $result->current();
        $assignmentType = $row->findParentRow('Application_Model_DbTable_AssignmentTypes');
        
        $assignment->setId($row->id)
                  ->setName($row->name)
                  ->setTypeId($row->type_id)
                  ->setTypeFriendlyName($assignmentType->friendly_name)
                  ->setTypeName($assignmentType->name)
                  ->setDescription($row->description)
                  ->setDue($row->due)
                  ->setMaxPoints($row->max_points)
                  ->setReleased(ord($row->released) == 1);
    } 
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $assignments   = array();
        foreach ($resultSet as $row) {
            $assignmentType = $row->findParentRow('Application_Model_DbTable_AssignmentTypes');
            $assignment = new Application_Model_Assignment();
            $assignment->setId($row->id)
                  ->setName($row->name)
                  ->setTypeId($row->type_id)
                  ->setTypeFriendlyName($assignmentType->friendly_name)
                  ->setTypeName($assignmentType->name)
                  ->setDescription($row->description)
                  ->setDue($row->due)
                  ->setMaxPoints($row->max_points)
                  ->setReleased(ord($row->released) == 1);
            $assignments[] = $assignment;
        }
        return $assignments;
    }

}

