<?php
namespace Keepict\Form;

use Zend\Form\Form;

class EditPictureForm extends Form{

    public function __construct($name = null){
        parent::__construct('edit');
        $this->setAttribute('method', 'post');
        
        $this->add(array(
    		'name' => 'id',
    		'attributes' => array(
				'type'  => 'hidden',
    		),
        ));
        
        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type' => 'text',
                'required' => true,           
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
    		'name' => 'description',
    		'attributes' => array(
				'type' => 'textarea',
    		    'rows' => 6,
				'required' => true,
				'class' => 'form-control'
    		)
        ));
        
        $this->add(array(
    		'name' => 'tags',
    		'attributes' => array(
				'type' => 'text',
				'class' => 'form-control',
    		)
        ));
        
        $this->add(array(
    		'type' => 'Zend\Form\Element\Select',
            'name' => 'category',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'search-select-100'
            )
        ));
        
        
        $this->add(array(
            'name' => 'save',
            'attributes' => array(
                'value' => "Enregistrer",
                'type' => 'submit',
                'class' => 'btn btn-primary'
            )
        ));
    }
}