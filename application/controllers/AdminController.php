<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->view->headScript()->appendFile($baseUrl.'/js/Admin.js');
        $this->view->headLink()->appendStylesheet($baseUrl.'/css/Admin.css');
    }

    public function indexAction()
    {
        // action body
        $umapper = new Application_Model_UserMapper();
        $users = $umapper->fetchAll();
        $hwScores = $hwGrades = $testGrades = $overallGrades = array();
        $gradeScale = new Zend_Config_Xml(APPLICATION_PATH.'/configs/GradeScale.xml', 'grades');
        foreach ($users as $user)
        {
            $uid = $user->getId();
            $uamapper = new Application_Model_UsersAssignmentsMapper();
            $uas = $uamapper->fetchByUser($user->getId());
            $hwScores[$uid] = 0;
            
            foreach($uas as $ua)
            {
                if ($ua->getModelAssignment()->getReleased())
                    $hwScores[$uid] += $ua->getScore();
            }
            
            foreach($gradeScale->hwScale->pts as $pts)
            {
                if($hwScores[$uid] >= $pts->amt)
                    $hwGrades[$uid] = number_format($pts->grade,1);
            }
            $test1 = $user->getModelTest()->getTest1();
            $test2 = $user->getModelTest()->getTest2();
            if ($test1 < 80 && $user->getModelTest()->getTestRetake1())
                $test1 = ( $test1 + 80 ) / 2;
            if ($test2 < 80 && $user->getModelTest()->getTestRetake2())
                $test2 = ( $test2 + 80 ) / 2;
            $testScore = ($test1 + $test2) / 2;
            
            foreach($gradeScale->testScale->pts as $pts)
            {
                if($testScore >= $pts->amt)
                    $testGrades[$uid] = number_format($pts->grade,1);
            }
            //total
            $hwTotal = $hwGrades[$uid] * $gradeScale->hwWeight;
            $testTotal = $testGrades[$uid] * $gradeScale->testWeight;
            
            $overallGrades[$uid] = number_format($hwTotal+$testTotal,1);
            if ($hwGrades[$uid] < 2.0 && $overallGrades[$uid] >= 2.0)
                $overallGrades[$uid] = 1.9;
            else if ($hwGrades[$uid] >= 2.0 && 
                ( ($user->getModelTest()->getTest1() < 80 && !$user->getModelTest()->getTestRetake1() ) ||
                 ($user->getModelTest()->getTest2() < 80 && !$user->getModelTest()->getTestRetake2() ) )
               )
                $overallGrades[$uid] = '1.0X';
            
            $user->setModelUserAssignments($uas);
        }  
        $amapper = new Application_Model_AssignmentMapper();
        $assignments = $amapper->fetchAll();
        
        $this->view->assignments = $assignments;
        $this->view->users = $users;
        $this->view->hwScores = $hwScores;
        $this->view->hwGrades = $hwGrades;
        $this->view->overallGrades = $overallGrades;
    }
    
    public function scoreTestAction()
    {
        $request = $this->getRequest();
        $umapper = new Application_Model_UserMapper();
        
        if ($request->isPost()) 
        {
                $values = $request->getPost();
                $form = new Application_Form_TestGrade();
                if ($form->isValidPartial($values))
                {
                    $ut = new Application_Model_UserTests($values);
                    $utmapper = new Application_Model_UserTestsMapper();
                    $utmapper->save($ut);
                    if ($values['ajax'])
                    {
                        //TODO: partial?
                        print "ok";
                        die();
                    }
                }
                else if ($values['ajax'])
                {
                    $messages = $form->getMessages();
                    foreach (array_keys($messages) as $name) 
                    {
                        foreach($messages[$name] as $k => $v) 
                        {
                            foreach($v as $a)
                            {
                                print $a;
                            }
                        }
                    }
                    die();
                }
        }
        
        $forms = new Zend_Form();
        $forms->removeDecorator('form');
        $users = $umapper->fetchAll();
        foreach ($users as $user)
        {
            $form = new Application_Form_TestGrade();
            $form->setLegend($user->getProperName());
            $form->getElement('UserId')->setValue($user->getId());
            $form->getElement('Test1')->setValue($user->getModelTest()->getTest1());
            $form->getElement('Test2')->setValue($user->getModelTest()->getTest2());
            $form->getElement('TestRetake1')->setValue($user->getModelTest()->getTestRetake1());
            $form->getElement('TestRetake2')->setValue($user->getModelTest()->getTestRetake2());
            $form->setAction($this->view->url());
            $forms->addSubForm($form, 'user_'.$user->getId());
        }
        
        
        $this->view->form = $forms;
    }


}

