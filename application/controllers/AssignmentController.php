<?php
class AssignmentController extends Zend_Controller_Action
{

    public function init()
    {
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
                $this->view->headScript()->appendFile($baseUrl.'/js/timepicker_plug/timepicker.js');
                $this->view->headScript()->appendFile($baseUrl.'/js/Assignment.js');
                $this->view->headLink()->appendStylesheet($baseUrl.'/css/timepicker_plug.css');
                $this->view->headLink()->appendStylesheet($baseUrl.'/css/Assignment.css');
    }

    public function indexAction()
    {
        // action body
        $mapper = new Application_Model_AssignmentMapper();
        $assignments = $mapper->fetchAll();
        
        $uas = array();
        $uamapper = new Application_Model_UsersAssignmentsMapper(); 
        $auth = Zend_Auth::getInstance();
        $user_id = $auth->getStorage()->read()->user_pk;
        foreach ($assignments as $assignment)
        {
            $ua = new Application_Model_UsersAssignments();
            $uamapper->findUserAssignment($user_id, $assignment->getId(), $ua);
            $uas[$assignment->getId()] = $ua;
        }
        
        $this->view->uas = $uas;
        $this->view->assignments = $assignments;
    }
    
    public function gradeAction()
    {
        $assignment_id = $this->_getParam("id");
        $request = $this->getRequest();
        if (!$assignment_id)
        { 
            if ($request->isPost())
            {
                $post = $request->getPost();
                $this->_helper->redirector->gotoSimple('grade','assignment', null, 
                                                            array('id' => $post['assignment_id']));
            }
            $form = new Application_Form_AssignmentList();
            $this->view->form = $form;
        }
        else
        {
            $ua = new Application_Model_UsersAssignments();
            $uamapper = new Application_Model_UsersAssignmentsMapper();
            
            $forms = new Zend_Form();
            $forms->removeDecorator('form');
            
            $assignment = new Application_Model_Assignment();
            $mapper = new Application_Model_AssignmentMapper();
            $mapper->find($assignment_id, $assignment);
            $this->view->assignment = $assignment;
            
            //Fill actions
            $umapper = new Application_Model_UserMapper();
            $this->view->users = $umapper->fetchAll();

            if ($request->isPost()) {
                $values = $request->getPost();
                $form = new Application_Form_Grade();
                if ($form->isValidPartial($values))
                {
                
                //foreach($request->getPost() as $values)
                //{
                    //print_r($request->getPost());
                    //die();
                    $ua = new Application_Model_UsersAssignments($values);
                    $ua->setGraded(true);
                    $uamapper->save($ua);
                    //TODO partial
                    if ($values['ajax'])
                    {
                        if ($ua->getScore() > $assignment->getMaxPoints())
                        {
                            print "gradewarn";
                            die();
                        }
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
            
            $uas = $uamapper->fetchByAssignment($assignment_id);
            foreach($uas as $ua)
            { 
                $form = new Application_Form_Grade();
                $form->setLegend($ua->getModelUser()->getProperName());
                $form->getElement('UserId')->setValue($ua->getModelUser()->getId());
                $form->getElement('AssignmentId')->setValue($ua->getModelAssignment()->getId());
                $form->getElement('Score')->setValue($ua->getScore());
                $form->getElement('Comment')->setValue($ua->getComment());
                $form->getElement('Id')->setValue($ua->getId());
                if ($ua->getUrl())
                    $form->addElement('text','html_url', array(
                        'value' => $ua->getUrl(),
                        'required' => false,
                        'size'   => 50,
                        'class'	=> 'html_url'
                    ));
                $form->setAction($this->view->url());
                $forms->addSubForm($form, 'user_'.$ua->getModelUser()->getId()); 
            }
            $this->view->form = $forms;
        }
        
    }

    public function editAction()
    {
        $assignment_id = $this->_getParam("id");
        $request = $this->getRequest();
        if (!$assignment_id)
        {
            if ($request->isPost())
            {
                $post = $request->getPost();
                $this->_helper->redirector->gotoSimple('edit','assignment', null, 
                                                            array('id' => $post['assignment_id']));
            }
            $form = new Application_Form_AssignmentList();
            $this->view->form = $form;
        }
        else
        {    
            $form    = new Application_Form_Assignment();
            $mapper = new Application_Model_AssignmentMapper();
            $assignment = new Application_Model_Assignment();
            
            if ($request->isPost()) {
                if ($form->isValid($request->getPost())) {
                    $assignment = new Application_Model_Assignment($form->getValues());
                    $assignment->setId($assignment_id);

                    $mapper->save($assignment);
                    $this->view->message = "Assignment saved.";
                }
            }
            else
            {
                $mapper->find($assignment_id, $assignment);
                $form->getElement('Name')->setValue($assignment->getName());
                $form->getElement('Description')->setValue($assignment->getDescription());
                $form->getElement('MaxPoints')->setValue($assignment->getMaxPoints());
                $form->getElement('TypeId')->setValue($assignment->getTypeId());
                $form->getElement('Due')->setValue($assignment->getDue());
                $form->getElement('Released')->setValue($assignment->getReleased());
                $form->getElement('submit')->setLabel('Edit');
            }
            
            
            $this->view->id_param = array('id' => $assignment_id);
            $this->view->form = $form;
        }
    }

    public function newAction()
    {
        $request = $this->getRequest();
        $form    = new Application_Form_Assignment();
 
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $assignment = new Application_Model_Assignment($form->getValues());
                $mapper = new Application_Model_AssignmentMapper();

                $mapper->save($assignment);
                $this->view->message = "Assignment created.";
            }
        }
        $this->view->form = $form;
    }

    public function submitAction()
    {
        $assignment_id = $this->_getParam("id");
        $request = $this->getRequest();
        if (!$assignment_id)
        {
            if ($request->isPost())
            {
                $post = $request->getPost();
                $this->_helper->redirector->gotoSimple('submit','assignment', null, 
                                                            array('id' => $post['assignment_id']));
            }
            $form = new Application_Form_AssignmentList();
            $this->view->form = $form;
        }
        else
        {
            $error = false;
            $assignment = new Application_Model_Assignment();         
            $mapper = new Application_Model_AssignmentMapper();
            $mapper->find($assignment_id, $assignment);
            $this->view->assignment = $assignment;
            $form = null;
            if ($assignment->getId() == null)
            {
                $this->view->message = "The assignment doesn't exist!";
                $error = true;
            }
            $dueDate = new Zend_Date($assignment->getDue(), Zend_Date::ISO_8601);
            if ($dueDate->isEarlier( Zend_Date::now() ))
            {
                $this->view->message = "The due date for this assignment has passed.";
                $error = true;
            }
            switch ($assignment->getTypeName())
            {
                case 'html':
                    $form = new Application_Form_AssignmentSubmitHTML();
                    break;
                case 'word':
                case 'excel':
                case 'powerpoint':
                    $form = new Application_Form_AssignmentSubmitFile();
                    break;
                case 'quiz':
                    $form = new Application_Form_AssignmentSubmitFile();
                    $form->getElement('file')->addValidator('Extension', false, 'docx');
                    break;
                case 'inclass':
                    $this->view->message = 'This assignment was done in class and cannot be submitted online.';
                    $error = true;
                    break;
                case 'email':
                    $this->view->message = 'This assignment must be submitted via email.';
                    $error = true;
                    break;
            }
            if ($request->isPost()) {
                if ($form->isValid($request->getPost()) && !$error) {
                    ///////////////////
                    //USERs_ASSIGNMENTs
                    $ua = new Application_Model_UsersAssignments($form->getValues());
                    $mapper = new Application_Model_UsersAssignmentsMapper();
                    //user_id
                    $auth = Zend_Auth::getInstance();
                    $user_id = $auth->getStorage()->read()->user_pk;
                    $userMapper = new Application_Model_UserMapper();
                    $user = new Application_Model_User();
                    $userMapper->find($user_id, $user);
                    $ua->setUserId($user_id);
                    //assignment_id
                    $ua->setAssignmentId($assignment_id);
                    $ua->setScore(0);
                    $ua->setGraded(false);
                    $mapper->findUserAssignment($user_id, $assignment_id, $ua);
                    
                    if ($form instanceof Application_Form_AssignmentSubmitFile)
                    {
                        
                        ////////////
                        //File stuff
                        $bootstrap = $this->getInvokeArg('bootstrap'); 
                        $options = $bootstrap->getOptions();
                        $filePath = $options['files']['directory'].$assignment->getName().'/';
                        
                        // -- unfortunately, zend has no wrappers for mkdir or is_dir...
                        // -- and thus I use...PHP...gasp!
                        if (!is_dir($filePath))
                            mkdir($filePath);
                        
                        try{
                            if (!$form->file->receive())
                            {
                                $this->view->message = "Error receiving the file";
                                $error = true;
                            }
                        } catch (Zend_File_Transfer_Exception $e){
                            $this->view->message = "Error receiving the file";
                            $error = true;
                        }
                        $orig_filename = $form->file->getFileName();
                        // -- oh, and PHP here, too :/
                        $ext = substr(strrchr($orig_filename, '.'), 1);
                        $new_filename = $assignment->getName().' - '.$user->getLastName().', '.$user->getFirstName().$ua->getSubmitAttempt().'.'.$ext;
                        $rename = $filePath.$new_filename;
                        $filterFileRename = new Zend_Filter_File_Rename(array('target' => $rename, 'overwrite' => true));
                        $filterFileRename->filter($orig_filename);
                        
                        // -- file saved to a file...now to mysql
                        $ua->setFile(file_get_contents($rename));
                        $ua->setFilename($new_filename);
                    }
                    if (!$error){    
                        $ua->setSubmitAttempt($ua->getSubmitAttempt() + 1);
                        $mapper->save($ua);
                        $this->view->message = "Assignment submitted.";
                    }
                }
            }
            $this->view->id_param = array('id' => $assignment_id);
            $this->view->form = $form;
        }
    }

    public function fillAction()
    {
        $assignment_id = $this->_getParam("id");
        $request = $this->getRequest();
        if (!$assignment_id)
        {
            $post = $request->getPost();
            $this->_helper->redirector->gotoSimple('grade','assignment', null, null);
        }
        else
        {
            $user_id = $this->_getParam("user");
            $umapper = new Application_Model_UserMapper();
            $uamapper = new Application_Model_UsersAssignmentsMapper();
            if (!$user_id)
            {
                $users = $umapper->fetchAll();
                foreach ($users as $user)
                {
                    $ua = new Application_Model_UsersAssignments();
                    $ua->setUserId($user->getId());
                    $ua->setAssignmentId($assignment_id);
                    $ua->setScore(0);
                    $ua->setGraded(false);
                    $uamapper->save($ua);
                }
            }
            else
            {
                $user = new Application_Model_User();
                $umapper->find($user_id,$user);
                $ua = new Application_Model_UsersAssignments();
                $ua->setUserId($user_id);
                $ua->setAssignmentId($assignment_id);
                $ua->setScore(0);
                $ua->setGraded(false);
                $uamapper->save($ua);
            }
            $this->_helper->redirector->gotoSimple('grade','assignment', null, array('id' => $assignment_id));
        }
    }
}