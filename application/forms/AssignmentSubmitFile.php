<?php

class Application_Form_AssignmentSubmitFile extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('enctype', 'multipart/form-data');

        $file = new Zend_Form_Element_File('file');
        $file->setRequired(true)
             ->setLabel('Upload assignment')
             ->addValidator('Count', false, 1)
             ->addValidator('Size', false, 10485760)
             ->addValidator('Extension',false,'xlsx,docx,pptx');
             //->setDestination('/var/www/uploads/');
        $this->addElement($file);
        
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

