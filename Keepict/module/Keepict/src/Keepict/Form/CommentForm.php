<?php
namespace Keepict\Form;

use Zend\Form\Form;

class CommentForm extends Form{

    public function __construct($name = null){
        parent::__construct('comment');
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
    		'name' => 'body',
    		'attributes' => array(
				'type' => 'textarea',
    		    'required' => true,
    		    'placeholder' => 'À vous de commenter !',
                'class' => 'form-control',
    		    'rows' => 3
    		)
        ));
              
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn btn-default'
            )
        ));
    }
}