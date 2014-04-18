<?php
namespace Keepict\Form;

use Zend\Form\Form;

class CreateAlbumForm extends Form{

    public function __construct($name = null){
        parent::__construct('create');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
        
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
    		'name' => 'name',
    		'attributes' => array(
				'type' => 'text',
    		    'required' => true,
    		    'placeholder' => 'Sois créatif =)',
                'class' => 'form-control'
    		),
            'options' => array(
        		'label' => 'Nom de l\'album',
                'required' => true
            )
        ));
        
        $this->add(array(
    		'name' => 'cover',
    		'attributes' => array(
				'type' => 'file',
    		),
    		'options' => array(
				'label' => 'Photo de couverture'
    		)
        ));
        
        $this->add(array(
    		'name' => 'description',
    		'attributes' => array(
				'type' => 'textarea',
				'placeholder' => 'Quelques lignes pour décrire ton album',
				'class' => 'form-control',
    		    'rows' => 10
    		),
            'options' => array(
        		'label' => 'Description'
            )
        ));
                        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn btn-primary',
            )
        ));
    }
}