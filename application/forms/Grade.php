<?php

class Application_Form_Grade extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setIsArray(true);
        //$this->setDecorators(array(
        //    'FormElements',
        //    'Fieldset',
        //    'Form'
        //));
        //$this->setElementDecorators(array(
        //    'ViewHelper',
        //    'Errors',
        //    array(array('data' => 'HtmlTag'), array('tag' => 'div')),
        //    array('Label', array('tag' => 'td')), 
        //));
        
        
        $element = new Zend_Form_Element_Hidden('Id');
        $element->setRequired(true)
                ->addDecorator('HtmlTag',array('tag' => 'span'))
                ->addDecorator('Label', array('tag' => 'span'));
        $this->addElement($element);

        $element = new Zend_Form_Element_Hidden('UserId');
        $element->setRequired(true)
                ->addDecorator('HtmlTag',array('tag' => 'span'))
                ->addDecorator('Label', array('tag' => 'span'));
        $this->addElement($element);

        $element = new Zend_Form_Element_Hidden('AssignmentId');
        $element->setRequired(true)
                ->addDecorator('HtmlTag',array('tag' => 'span'))
                ->addDecorator('Label', array('tag' => 'span'));
        $this->addElement($element);
        
        $this->addElement('text', 'Score', array(
            'class'		 => 'Score',
            'label'      => 'Score',
            'required'   => true,
            'validators' => array(
                'Digits'
            )
        ));
        
        $this->addElement('textarea', 'Comment', array(
            'class'		 => 'Comment',
        	'label'      => 'Comment',
            'required'   => false
        ));
        
        $this->setDecorators(
            array(
                'PrepareElements', 
                array('viewScript', array('viewScript' =>  'form/grade.phtml'))
                ));
        
        // Add the submit button
        //$this->addElement('submit', 'submit', array(
        //    'ignore'   => true,
        //    'label'    => 'Submit',
        //));
 
        // And finally add some CSRF protection
        //$this->addElement('hash', 'csrf', array(
        //    'ignore' => true,
        //));
      
    }


}

