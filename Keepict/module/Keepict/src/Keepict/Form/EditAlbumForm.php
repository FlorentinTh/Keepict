<?php
namespace Keepict\Form;

use Zend\Form\Form;

class EditAlbumForm extends Form{

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
            'name' => 'name',
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
            'name' => 'save',
            'attributes' => array(
                'value' => "Enregistrer",
                'type' => 'submit',
                'class' => 'btn btn-primary'
            )
        ));
    }
}