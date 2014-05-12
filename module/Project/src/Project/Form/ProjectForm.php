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
                'class' => 'form-control',
                'placeholder' => 'D&D, Warhammer, ect.'
            ),
            'options' => array(
                'label' => 'Game',
            ),
        ));
        $this->add(array(
            'name' => 'count',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
                'placeholder' => 'Numbers of Minis'
            ),
            'options' => array(
                'label' => 'Count',
            ),
        ));

        $this->add(array(
            'name' => 'budget',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
                'placeholder' => 'In US Dollars'
            ),
            'options' => array(
                'label' => 'Budget',
            ),
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'detail_level',
             'options' => array(
                     'label' => 'Detail Level',
                     'value_options' => array(
                             'low' => 'Low',
                             'meduium' => 'Medium',
                             'high' => 'High',
                     ),
             ),
             'attributes' => array(
                'class' => 'form-control',
            ),
     ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-success'
            ),
        ));
    }
}