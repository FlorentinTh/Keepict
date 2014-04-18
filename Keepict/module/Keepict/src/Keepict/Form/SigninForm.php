<?php
namespace Keepict\Form;

use Zend\Form\Form;

class SigninForm extends Form{

    public function __construct($name = null){
        parent::__construct('signin');
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
    		'name' => 'courriel',
    		'attributes' => array(
				'type' => 'Zend\Form\Element\Email',
    		    'required' => true,
    		    'placeholder' => 'Courriel',
                'class' => 'form-control'
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
                'value' => "Se connecter",
                'type' => 'submit',
                'class' => 'btn btn-lg btn-success btn-block'
            )
        ));
    }
}