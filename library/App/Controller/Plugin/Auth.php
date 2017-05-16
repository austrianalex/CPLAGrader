<?php
class App_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    private $_acl = null;
   
    public function __construct(Zend_Acl $acl)
    {
        $this->_acl = $acl;
    }
   
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity()){
                $storage = $auth->getStorage()->read();
                $role = $storage->role;
            } else {
                $role = null;
            }

            $controller = $request->controller;
            $action = $request->action;

            $isAllowed = $this->_acl->isAllowed($role, $controller, $action);

            if(!$isAllowed) {
                $request//->setModuleName('auth')
                        ->setControllerName('login')
                        ->setActionName('index');
            }
    }
}