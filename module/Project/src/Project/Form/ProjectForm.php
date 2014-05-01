<?php
namespace Project\Form;

use Zend\Form\Form;

class ProjectForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('project');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'game',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Game',
            ),
        ));
        $this->add(array(
            'name' => 'count',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Count',
            ),
        ));

        $this->add(array(
            'name' => 'budget',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Budget',
            ),
        ));

        $this->add(array(
            'name' => 'detail_level',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Detail Level',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}