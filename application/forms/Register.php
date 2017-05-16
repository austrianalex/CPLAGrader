<?php

class Application_Form_Register extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');

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
 
        $this->addElement('password', 'Password', array(
            'label'      => 'Password (6-20 chars)',
            'required'   => true,
            'validators' => array(
                'NotEmpty',
                array('StringLength', false, array(6, 20))
                )
        ));
        $this->addElement('password', 'Password2', array(
            'label'      => 'Re-enter Password',
            'required'   => true,
            'validators' => array(
                'NotEmpty',
                array('StringLength', false, array(6, 20)),
                array('Identical', false, array('token'=>'Password'))
                )
        ));
        
        $this->addElement('text', 'ValidationCode',array(
            'label' => 'Validation code',
            'required' => true
        ));
 
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Register',
        ));
 
        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }

}

