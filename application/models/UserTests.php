<?php

class Application_Model_UserTests
{
    protected $_user_id;
    protected $_test1;
    protected $_test2;
    protected $_retake1;
    protected $_retake2;

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
    
    public function getUserId(){return $this->_user_id;}
    public function setUserId($user_id)
    {
        $this->_user_id = $user_id;
        return $this;
    }
    public function getTest1(){return $this->_test1;}
    public function setTest1($test1)
    {    
        $this->_test1 = $test1;
        return $this;
    }
    
    public function getTest2(){return $this->_test2;}
    public function setTest2($test2)
    {    
        $this->_test2 = $test2;
        return $this;
    }
    
    public function getTestRetake1(){return $this->_retake1;}
    public function setTestRetake1($retake1)
    {
        $this->_retake1 = $retake1;
        return $this;
    }
    
    public function getTestRetake2(){return $this->_retake2;}
    public function setTestRetake2($retake2)
    {
        $this->_retake2 = $retake2;
        return $this;
    }

}

