<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /*protected $_front;
    protected $_view;
    */
    protected $_acl;
    
    protected function _initPlugins(){ 
        $fc = Zend_Controller_Front::getInstance();
        $this->_acl = new App_Acl();
        $auth = new App_Controller_Plugin_Auth($this->_acl);
        $fc->registerPlugin($auth);
    }
    
    protected function _initViewController()
    {
        $this->bootstrap('view');
        $this->bootstrap('FrontController');
        $this->_view = $this->getResource('view');
        $this->_front = $this->getResource('FrontController');
    }
    
	protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');

        $navContainerConfig = new Zend_Config_Xml(APPLICATION_PATH.'/configs/nav.xml', 'nav');
        $navContainer = new Zend_Navigation($navContainerConfig);
        
        ini_set("session.gc_maxlifetime", 86400);
        
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()){
            $role = $auth->getStorage()->read()->role;
        }else{
            $role = null;
        }
        $view->navigation($navContainer)->setAcl($this->_acl)
                                        ->setRole($role);
    }
    protected function _initAutoload()
    {
        $loader = new Zend_Application_Module_Autoloader(array(
        	'namespace' => 'Application',
        	'basePath' => APPLICATION_PATH
        ));
        return $loader;
    }

    /*protected function _initLogin()
    {
        $router = $this->_front->getRouter();
        $req = new Zend_Controller_Request_Http();
        $router->route($req);
        $module = $req->getModuleName();
    
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->_view->login = (array) $auth->getIdentity();
        } else {
            $this->_view->login = NULL;
            if ($module == 'admin') {
                $response = new Zend_Controller_Response_Http();
                $response->setRedirect('/');
                $this->_front->setResponse($response);
            }
        }
    }*/

}

