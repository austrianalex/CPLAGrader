<?php
class App_Acl extends Zend_Acl {

    public function __construct()
    {
        //--------------------
        // Roles
        // -------------------
        $this->addRole(new Zend_Acl_Role('guest'));
        $this->addRole(new Zend_Acl_Role('Student'), 'guest');
        $this->addRole(new Zend_Acl_Role('TA'), 'Student');
        $this->addRole(new Zend_Acl_Role('Instructor'), 'TA');

        // -------------------
        // Resources
        // -------------------
        $this->addResource(new Zend_Acl_Resource('index'));
        $this->addResource(new Zend_Acl_Resource('login'));
        //$this->addResource(new Zend_Acl_Resource('register'), 'login');
        //$this->addResource(new Zend_Acl_Resource('logout'), 'login');
        $this->addResource(new Zend_Acl_Resource('assignment'));
        $this->addResource(new Zend_Acl_Resource('admin'));
        $this->addResource(new Zend_Acl_Resource('user'));
        $this->addResource(new Zend_Acl_Resource('download'));
        //$this->addResource(new Zend_Acl_Resource('grade'), 'assignment');
        //$this->addResource(new Zend_Acl_Resource('new'), 'assignment');
        //$this->addResource(new Zend_Acl_Resource('submit'), 'assignment');
        
        // ---
        // Guests
        // ---
        $this->allow(null,'login', array('login','register'));
        // ---
        // Students
        // ---  
        $this->allow('Student','login',array('logout'));
        $this->allow('Student','index');
        $this->allow('Student','assignment',array('assignment','index','submit'));
        $this->allow('Student','download');
        $this->deny ('Student','login',array('register','login'));
        // ---
        // TA
        // ---
        $this->allow('TA','assignment',array('grade','fill'));
        // ---
        // Instructors
        // ---
        $this->allow('Instructor','user');
        $this->allow('Instructor','assignment');
        $this->allow('Instructor','admin');
        
    }
}