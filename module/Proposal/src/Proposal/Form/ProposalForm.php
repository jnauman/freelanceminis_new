<?php
namespace Proposal\Form;

use Zend\Form\Form;

class ProposalForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('proposal');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'project_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'est_cost',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Estimated Cost',
            ),
        ));
        $this->add(array(
            'name' => 'est_time',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Estimated Time',
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