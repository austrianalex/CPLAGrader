<?php

class Application_Form_AssignmentSubmitHTML extends Zend_Form
{

    public function init()
    {
         $this->setMethod('post');
         $this->addElement('text', 'Url', array(
             'label' => 'URL (e.g. http://studentweb.ewu.edu/YOURUSERNAME/cpla/index.html)',
             'validators' => array(
                array('validator' => new App_UrlValidator()),
                array('regex', false, '/^http:\/\/studentweb.ewu.edu\/.+/i')
             )
         ));
         
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));
 
        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }


}

