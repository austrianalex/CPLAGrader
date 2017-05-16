<?php

class LoginController extends Zend_Controller_Action
{
    var $siteConfig = null;
    public function init()
    {
        /* Initialize action controller here */
        if (Zend_Auth::getInstance()->hasIdentity()) {
            if ('logout' != $this->getRequest()->getActionName()) {
                $this->_helper->redirector('index', 'index');
            }
        }
        $this->siteConfig = new Zend_Config_Xml(APPLICATION_PATH.'/configs/SiteConfig.xml');
    }

    public function indexAction()
    {    
        $request = $this->getRequest();
        $form    = new Application_Form_Login();
        
 
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                    $user = new Application_Model_User($form->getValues());
                    $resource = $this->getFrontController()->getParam('bootstrap')->getPluginResource('db');
                    $authAdapter = new Zend_Auth_Adapter_DbTable(
                        $resource->getDbAdapter(),
                        'users',
                        'username',
                        'password'
                    );
                    $authAdapter->setIdentity($user->getUsername())
                                ->setCredential($user->getPassword());
                    $auth = Zend_Auth::getInstance();
                    $result = $auth->authenticate($authAdapter);
                    if ($result->isValid())
                    {
                        $mapper = new Application_Model_RoleMapper();
                        $res = $authAdapter->getResultRowObject();
                        
                        $identity = new stdClass();
                        $identity->user_pk = $res->id;
                        $identity->username = $res->username;
                        $identity->role = $mapper->getRoleName($res->role_id);
                        
                        $auth->getStorage()->write($identity);
                        
                        $this->_helper->redirector('index', 'index');
                    }
                    else
                    {
                        $form->setDescription('Invalid credentials provided');
                    }
                
            }
        }
        $this->view->form = $form;
    }

    public function registerAction()
    {
        // action body
        $request = $this->getRequest();
        $form    = new Application_Form_Register();
 
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $user = new Application_Model_User($form->getValues());
                $user->setRoleId("3"); //want them to be a student
                $user->setId(null); //don't want them editing someone else
                $mapper = new Application_Model_UserMapper();
                $error = false;
                if (!$mapper->usernameAvailable($user->getUsername()))
                {
                    $form->getElement('Username')->addError("The username ".$user->getUsername()
                            ." has already been taken. Please choose another.");
                    $error = true;
                }
                if ($form->getElement('ValidationCode')->getValue() != $this->siteConfig->validation)
                {
                    $form->getElement('ValidationCode')->addError("The code you entered was incorrect (check your syllabus for the code).");
                    $error = true;
                }
                
                if (!$error)
                {
                    $mapper->save($user);
                    //$this->sendVerificationEmail();
                    $this->view->message = "Thanks, you have been registered. You may now login.";
                }
            }
        }
        $this->view->form = $form;
    }
    
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
    }
    /**
     * TODO: NOT IMPLEMENTED
     */
    private function sendVerificationEmail()
    {
        $mail = new Zend_Mail();
        $mail->setBodyText('Registration is almost complete! Please visit the link below to activate your account\n'
                .Zend_Controller_Front::getInstance()->getBaseUrl().'/login/activate?ValidationCode=')
             ->setFrom('somebody@example.com', 'Some Sender')
             ->addTo('somebody_else@example.com', 'Some Recipient')
             ->setSubject('TestSubject')
             ->send();
    }


}



