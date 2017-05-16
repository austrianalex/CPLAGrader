<?php

class Application_Form_AssignmentList extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');

        $types = new Application_Model_DbTable_Assignments();
        $optionsRows = $types->fetchAll();
        $options = array();
        foreach ($optionsRows as $optionRow)
        {
            $options[$optionRow->id] = $optionRow->name;
        }
        
        $this->addElement('select', 'assignment_id', array(
            'label' => 'Choose an assignment',
            'required' => true,
            'multiOptions' => $options
        ));
        
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));
 
        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }


}

