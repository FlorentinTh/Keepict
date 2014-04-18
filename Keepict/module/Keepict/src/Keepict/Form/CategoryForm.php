<?php
namespace Keepict\Form;

use Zend\Form\Form;

class CategoryForm extends Form{

    public function __construct($name = null){
        parent::__construct('category');
        $this->setAttribute('method', 'post');
        
        $this->add(array(
    		'type' => 'Zend\Form\Element\Csrf',
    		'name' => 'csrf'
		));

        $this->add(array(
    		'name' => 'id',
    		'attributes' => array(
				'type'  => 'hidden',
    		),
        ));
        
        $this->add(array(
    		'name' => 'wording',
    		'attributes' => array(
				'type' => 'text',
    		    'required' => true,
    		    'placeholder' => 'Libellé de la nouvelle catégorie',
                'class' => 'form-control',
    		),
            'options' => array(
        		'label' => 'Libellé'
            )
        ));
              
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn btn-primary'
            )
        ));
    }
}