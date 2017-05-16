<?php

class Application_Model_UsersAssignments
{
    protected $_id;
    protected $_user_id;
    protected $_assignment_id;
    protected $_score;
    protected $_graded;
    protected $_comment;
    protected $_submit_attempt;
    protected $_model_user;
    protected $_model_assignment;
    protected $_url;
    protected $_file;
    protected $_filename;
    
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
    public function compareTo($ua1, $ua2)
    {
        return Application_Model_User::compareTo($ua1->getModelUser(),$ua2->getModelUser());
    }
    
    public function getId(){return $this->_id;}
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }
    
    public function getUserId(){return $this->_user_id;}
    public function setUserId($user_id)
    {
        $this->_user_id = $user_id;
        return $this;
    }
    
    public function getAssignmentId(){return $this->_assignment_id;}
    public function setAssignmentId($assignment_id)
    {
        $this->_assignment_id = $assignment_id;
        return $this;
    }
    
    public function getScore(){return $this->_score;}
    public function setScore($score)
    {
        $this->_score = $score;
        return $this;
    }
    
    public function getGraded(){return $this->_graded;}
    public function setGraded($graded)
    {
        $this->_graded = $graded;
        return $this;
    }
    
    public function getComment(){return $this->_comment;}
    public function setComment($comment)
    {
        $this->_comment = $comment;
        return $this;
    }
    
    public function getSubmitAttempt(){return $this->_submit_attempt;}
    public function setSubmitAttempt($submit_attempt)
    {
        $this->_submit_attempt = $submit_attempt;
        return $this;
    }
    
    public function getModelUser(){return $this->_model_user;}
    public function setModelUser(Application_Model_User $user)
    {
        $this->_model_user = $user;
        return $this;
    }
    
    public function getModelAssignment(){return $this->_model_assignment;}
    public function setModelAssignment(Application_Model_Assignment $assignment)
    {
        $this->_model_assignment = $assignment;
        return $this;
    }
    
    public function getUrl(){return $this->_url;}
    public function setUrl($url)
    {
        $this->_url = $url;
        return $this;
    }
    
    public function getFile(){return $this->_file;}
    public function setFile($file)
    {
        $this->_file = $file;
        return $this;
    }
    
    public function getFilename(){return $this->_filename;}
    public function setFilename($filename)
    {
        $this->_filename = $filename;
        return $this;
    }

}

