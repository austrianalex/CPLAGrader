<?php
class Application_Model_Assignment
{
    protected $_id;
    protected $_name;
    protected $_description;
    protected $_max_points;
    protected $_due;
    protected $_type_id;
    protected $_type_friendly_name;
    protected $_type_name;
    protected $_released;

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
            throw new Exception('Invalid assignments property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid assignments property');
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
    
    public function getId(){return $this->_id;}
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }
    
    public function getTypeId(){return $this->_type_id;}
    public function setTypeId($type_id)
    {
        $this->_type_id = $type_id;
        return $this;
    }
    
    public function getTypeName(){return $this->_type_name;}
    public function setTypeName($type_name)
    {
        $this->_type_name = $type_name;
        return $this;
    }
    
    public function getTypeFriendlyName(){return $this->_type_friendly_name;}
    public function setTypeFriendlyName($type_friendly_name)
    {
        $this->_type_friendly_name = $type_friendly_name;
        return $this;
    }
    
    public function getName(){return $this->_name;}
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }
    
    public function getDue(){return $this->_due;}
    public function setDue($due)
    {
        $this->_due = $due;
        return $this;
    }
    
    public function getDescription(){return $this->_description;}
    public function setDescription($description)
    {
        $this->_description = $description;
        return $this;
    }
    
    public function getMaxPoints(){return $this->_max_points;}
    public function setMaxPoints($maxPoints)
    {
        $this->_max_points = $maxPoints;
        return $this;
    }
    
    public function getReleased(){return $this->_released;}
    public function setReleased($released)
    {
        $this->_released = $released;
        return $this;
    }
    
}

