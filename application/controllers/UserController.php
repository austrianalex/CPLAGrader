<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $user_id = $this->_getParam("id");
        $request = $this->getRequest();
        if(!$user_id)
        {
            if ($request->isPost())
            {
                $post = $request->getPost();
                $this->_helper->redirector->gotoSimple('index','user', null, 
                                                            array('id' => $post['user_id']));
            }
            $form = new Application_Form_UserList();
        }
        else
        {
            $user = new Application_Model_User();
            $umapper = new Application_Model_UserMapper();
            $umapper->find($user_id,$user);
            
            $form = new Application_Form_User();
            
            if ($request->isPost() && $form->isValid($request->getPost()))
            {
                $user = new Application_Model_User($form->getValues());
                $umapper->save($user);
                $this->view->message = "User saved.";
            }
            
            $form->getElement('Id')->setValue($user->getId());
            $form->getElement('Username')->setValue($user->getUsername());
            $form->getElement('LastName')->setValue($user->getLastName());
            $form->getElement('FirstName')->setValue($user->getFirstName());
            $form->getElement('Email')->setValue($user->getEmail());
            $form->getElement('RoleId')->setValue($user->getRoleId());
        }
        
        $this->view->form = $form;
    }
	
	private function indexAdmin()
	{
	
	}

	private function indexUser()
	{
	
	}
}

