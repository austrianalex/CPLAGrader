<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'Username', array(
            'label'      => 'Username',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(5, 20))
            )
        ));
        
        $this->addElement('password', 'Password', array(
            'label'      => 'Password',
            'required'   => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(6, 20))
                )
        ));
        
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Login',
        ));
 
        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }


}

