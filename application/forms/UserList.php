<?php

class Application_Form_UserList extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');

        $umapper = new Application_Model_UserMapper();
        $users = $umapper->fetchAll();
        $options = array();
        foreach ($users as $user)
        {
            $options[$user->getId()] = $user->getLastName().', '.$user->getFirstName();
        }
        
        $this->addElement('select', 'user_id', array(
            'label' => 'Choose a user',
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

