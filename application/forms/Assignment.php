<?php

class Application_Form_Assignment extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'Name', array(
            'label'      => 'Name',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));
        
        $this->addElement('textarea', 'Description', array(
            'label'      => 'Description',
            'class'      => 'tinymce',
            'required'   => false
        ));
        
        $this->addElement('text', 'MaxPoints', array(
            'label' => 'Max Score',
            'required' => true,
        ));
        
        $types = new Application_Model_DbTable_AssignmentTypes();
        $optionsRows = $types->fetchAll();
        $options = array();
        $options[0] = "Select one...";
        foreach ($optionsRows as $optionRow)
        {
            $options[$optionRow->id] = $optionRow->friendly_name;
        }
        
        $this->addElement('select', 'TypeId', array(
            'label' => 'Assignment Type',
            'required' => true,
            'multiOptions' => $options,
            'disable' => array('0'),
            'value' => '0'
        ));
        
        $this->addElement('text', 'Due', array(
            'label' => 'Due Timestamp',
            'required' => true
        ));
        
        $this->addElement('checkbox', 'Released', array(
            'label' => 'Released/Graded',
            'required' => true
        ));
        
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Create',
        ));
 
        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }


}

