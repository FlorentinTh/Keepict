<?php
namespace Keepict\Form;

use Zend\Form\Form;

class SignupForm extends Form{

    public function __construct($name = null){
        parent::__construct('signup');
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
            'name' => 'firstname',
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Prénom',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
    		'name' => 'lastname',
    		'attributes' => array(
				'type' => 'text',
    		    'required' => true,
    		    'placeholder' => 'Nom',
                'class' => 'form-control'
    		)
        ));
        
        $this->add(array(
    		'name' => 'courriel',
    		'attributes' => array(
				'type' => 'Zend\Form\Element\Email',
    		    'required' => true,
    		    'placeholder' => 'Courriel',
                'class' => 'form-control'
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
                'value' => "Créer un compte",
                'type' => 'submit',
                'class' => 'btn btn-lg btn-primary btn-block'
            )
        ));
    }
}