<?php

class DownloadController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $file = $this->_getParam('file');
        $ua_id = $this->_getParam('id');
        
        $uamapper = new Application_Model_UsersAssignmentsMapper();
        $ua = new Application_Model_UsersAssignments();
        $uamapper->find($ua_id, $ua);
        $auth = Zend_Auth::getInstance();
        $user_id = $auth->getStorage()->read()->user_pk;
        
        if ($ua->getModelUser() == null || $ua->getModelUser()->getId() != $user_id || !$uamapper->loadFileAssignment($ua)) 
        {
	        $this->view->message = 'You do not have access to download this file.';
	        $this->_forward('error', 'download');
	        return FALSE;
	    }
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$ua->getFilename().'"');
 
	    // disable the view ... and perhaps the layout
	    $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        echo $ua->getFile();
        
    }
    public function errorAction()
    {
        
    }


}

