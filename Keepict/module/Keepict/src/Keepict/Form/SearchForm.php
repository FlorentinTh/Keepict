<?php
namespace Keepict\Form;

use Zend\Form\Form;

class SearchForm extends Form{

    public function __construct($name = null){
        parent::__construct('search');
        $this->setAttribute('method', 'post');
        
        $this->add(array(
    		'name' => 'predicate',
    		'attributes' => array(
				'type' => 'text',
    		    'required' => true,
    		    'placeholder' => 'Rechercher',
                'class' => 'form-control',
    		    'id' => 'nav-search'
    		),
            'options' => array(
        		'label' => 'Libellé'
            )
        ));
    }
}