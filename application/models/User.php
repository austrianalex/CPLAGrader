<?php

class Application_Model_User
{
    protected $_id;
    protected $_username;
    protected $_email;
    protected $_first_name;
    protected $_last_name;
    protected $_role_id;
    protected $_password;
    protected $_model_test;
    protected $_model_userassignments;

    public function __construct(array $options=null)
    {
        if (is_array($options))
        {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid users property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid users property');
        }
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $k = explode('_',$key);
            $newKey = 'set';
            foreach($k as $l)
            {
                $newKey .= ucfirst($l);
            }
            $method = $newKey;
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    
    public function compareTo($user1, $user2)
    {
        return strcmp($user1->getLastName().$user1->getFirstName(),$user2->getLastName().$user2->getFirstName());
    }
    
    public function getId(){return $this->_id;}
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }
    public function getPassword(){return $this->_password;}
    public function setPassword($password)
    {    
        $this->_password = Zend_Crypt::hash('md5', $password);
        return $this;
    }
    
    public function getEmail(){return $this->_email;}
    public function setEmail($email)
    {
        $this->_email = $email;
        return $this;
    }
    
    public function getUsername(){return $this->_username;}
    public function setUsername($username)
    {
        $this->_username = $username;
        return $this;
    }
    
    public function getRoleId(){return $this->_role_id;}
    public function setRoleId($role_id)
    {
        $this->_role_id = $role_id;
        return $this;
    }
    
    public function getFirstName(){return $this->_first_name;}
    public function setFirstName($first_name)
    {
        $this->_first_name = $first_name;
        return $this;
    }
    
    public function getLastName(){return $this->_last_name;}
    public function setLastName($last_name)
    {
        $this->_last_name = $last_name;
        return $this;
    }
    
    public function getModelTest(){return $this->_model_test;}
    public function setModelTest($model_test)
    {
        $this->_model_test = $model_test;
        return $this;
    }
    
    public function getModelUserAssignments(){return $this->_model_userassignments;}
    public function setModelUserAssignments($uas)
    {
        $this->_model_userassignments = $uas;
        return $this;
    }
    
    public function getProperName(){return $this->_last_name.', '.$this->_first_name;}
}

