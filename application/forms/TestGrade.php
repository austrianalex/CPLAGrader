<?php

class Application_Form_TestGrade extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setMethod('post');
        $this->setIsArray(true);
        $this->setDecorators(array(
            'FormElements',
            'Fieldset',
            'Form'
        ));
        $this->setElementDecorators(array(
            'ViewHelper',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div')),
            array('Label', array('tag' => 'td')), 
        ));
        
        $element = new Zend_Form_Element_Hidden('UserId');
        $element->setRequired(true)
                ->addDecorator('HtmlTag',array('tag' => 'span'))
                ->addDecorator('Label', array('tag' => 'span'));
        $this->addElement($element);

        $this->addElement('text', 'Test1', array(
            'label'      => 'Lit 1 Score',
            'required'   => true,
            'validators' => array(
                'Digits'
            )
        ));
        $this->addElement('checkbox','TestRetake1', array(
            'label' => 'Test 1 Retake'
        ));
        $this->addElement('text', 'Test2', array(
            'label'      => 'Lit 2 Score',
            'required'   => true,
            'validators' => array(
                'Digits'
            )
        ));
        $this->addElement('checkbox','TestRetake2', array(
            'label' => 'Test 2 Retake'
        ));
    }


}

