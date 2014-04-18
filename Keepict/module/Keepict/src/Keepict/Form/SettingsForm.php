<?php
namespace Keepict\Form;

use Zend\Form\Form;

class SettingsForm extends Form{

    public function __construct($name = null){
        parent::__construct('settings');
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
    		'name' => 'lastname',
    		'attributes' => array(
				'type' => 'text',
    		    'required' => true,
    		    'placeholder' => 'Nom',
                'class' => 'form-control'
    		),
            'options' => array(
        		'label' => 'Nom'
            )
        ));
        
        $this->add(array(
    		'name' => 'firstname',
    		'attributes' => array(
				'type' => 'text',
				'required' => true,
				'placeholder' => 'Prénom',
				'class' => 'form-control'
    		),
    		'options' => array(
				'label' => 'Prénom'
    		)
        ));
        
        $this->add(array(
    		'name' => 'courriel',
    		'attributes' => array(
				'type' => 'Zend\Form\Element\Email',
    		    'required' => true,
    		    'placeholder' => 'Courriel',
                'class' => 'form-control'
    		),
            'options' => array(
        		'label' => 'Courriel'
            )
        ));
        
        $this->add(array(
    		'name' => 'birth',
    		'attributes' => array(
    		    'id' => 'birth',
				'type' => 'text',
				'required' => true,
				'placeholder' => 'Date de naissance (AAAA-MM-JJ)',
				'class' => 'form-control',
    		),
            'options' => array(
        		'label' => 'Date de naissance'
            )
        ));
        
        $this->add(array(
    		'name' => 'avatar',
    		'attributes' => array(
				'type' => 'file',
    		),
    		'options' => array(
				'label' => 'Avatar'
    		)
        ));
        
        $this->add(array(
    		'name' => 'password',
    		'attributes' => array(
				'type' => 'password',
				'required' => true,
				'placeholder' => 'Password',
				'class' => 'form-control'
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