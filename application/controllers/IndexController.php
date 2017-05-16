<?php
/*
 * TODO: Create download link for grading assignments
 * TODO: Admin partial loading of everyones score
 * TODO: Create/Grade/edit link on main assignments page
 * TODO: Registration to send email and forget password
 * TODO: delete entries for grading
 */

class IndexController extends Zend_Controller_Action
{
    var $siteConfig = null;
    public function init()
    {
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $this->view->headLink()->appendStylesheet($baseUrl.'/css/Index.css');
        $this->view->headScript()->appendFile($baseUrl.'/js/Index.js');
        $this->siteConfig = new Zend_Config_Xml(APPLICATION_PATH.'/configs/SiteConfig.xml');
    }

    public function indexAction()
    {
        $uamapper = new Application_Model_UsersAssignmentsMapper(); 
        $auth = Zend_Auth::getInstance();
        $user_id = $auth->getStorage()->read()->user_pk;

        $ua = new Application_Model_UsersAssignments();
        
        $uas = $uamapper->fetchByUser($user_id);
        //hw
        $hwScore = 0;
        $hwGrade = 0;
        foreach($uas as $ua)
        {
            if ($ua->getModelAssignment()->getReleased())
                $hwScore += $ua->getScore();
        }
 
        $gradeScale = new Zend_Config_Xml(APPLICATION_PATH.'/configs/GradeScale.xml', 'grades');
        foreach($gradeScale->hwScale->pts as $pts)
        {
            if($hwScore >= $pts->amt)
                $hwGrade = $pts->grade;
        }
        
        $this->view->hwScore = $hwScore;
        $this->view->hwGrade = number_format($hwGrade,1);
        //test
        $user = new Application_Model_User();
        $mapper = new Application_Model_UserMapper();
        $mapper->find($user_id,$user);
        
        $test1 = $user->getModelTest()->getTest1();
        $test2 = $user->getModelTest()->getTest2();
        if ($test1 < 80 && $user->getModelTest()->getTestRetake1())
            $test1 = ( $test1 + 80 ) / 2;
        if ($test2 < 80 && $user->getModelTest()->getTestRetake2())
            $test2 = ( $test2 + 80 ) / 2;
        $testScore = ($test1 + $test2)/2;
        foreach($gradeScale->testScale->pts as $pts)
        {
            if($testScore >= $pts->amt)
                $testGrade = $pts->grade;
        }
        //total
        $hwTotal = $hwGrade * $gradeScale->hwWeight;
        $testTotal = $testGrade * $gradeScale->testWeight;
        
        $overallGrade = number_format($hwTotal+$testTotal,1);
        if ($hwGrade < 2.0 && $overallGrade >= 2.0)
            $overallGrade = 1.9;
        else if ($hwGrade >= 2.0 && 
               (  ($user->getModelTest()->getTest1() < 80 && !$user->getModelTest()->getTestRetake1() ) ||
                 ($user->getModelTest()->getTest2() < 80 && !$user->getModelTest()->getTestRetake2() ) )
               )
            $overallGrade = '1.0X';
        $this->view->user = $user;
        $this->view->testGrade = number_format($testGrade,1);
        $this->view->overallGrade = is_float($overallGrade) ? number_format($overallGrade, 1): $overallGrade;
        //////
        //RSS
        /////
        
        $channel = new Zend_Feed_Rss($this->siteConfig->feeds->main);
        $this->view->channel = $channel;
    }

}



