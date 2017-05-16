<?php

class Application_Form_User extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setMethod('post');
        
        $this->addElement('hidden', 'Id', array(
            'required' => true,
        ));
        $this->addElement('text', 'Username', array(
            'label'      => 'Username (5-20 chars)',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'alnum',
                array('validator' => 'StringLength', 'options' => array(5, 20))
            )
        ));
        
        $this->addElement('text', 'FirstName', array(
            'label'      => 'First Name',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 50))
            )
        ));
        
        $this->addElement('text', 'LastName', array(
            'label'      => 'Last Name',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 50))
            )
        ));
        
        // Add an email element
        $this->addElement('text', 'Email', array(
            'label'      => 'Your email address:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress', 
                array('StringLength', false, array(0, 255))
            )
        ));
        
        $types = new Application_Model_DbTable_Roles();
        $optionsRows = $types->fetchAll();
        $options = array();
        foreach ($optionsRows as $optionRow)
        {
            $options[$optionRow->id] = $optionRow->name;
        }
        
        $this->addElement('select', 'RoleId', array(
            'label' => 'Role',
            'required' => true,
            'multiOptions' => $options
        ));
 
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Edit Profile',
        ));
 
        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }


}

