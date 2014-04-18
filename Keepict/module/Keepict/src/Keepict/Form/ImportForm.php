<?php 
namespace Keepict\Form;

use Zend\Form\Form;
use Zend\InputFilter;
use Zend\Form\Element;
 
class ImportForm extends Form
{    
    public function __construct($name = null, $options = array()) {
    	parent::__construct($name, $options);
    	$this->addElements();
    	$this->addInputFilter();
    }
    
    public function addElements() {
        $select = new Element\Select('select');
        $select->setLabel('Choisissez un album')
               ->setAttribute('id', 'search-select');
        
    	$file = new Element\File('pictures');
    	$file->setLabel('Sélectionnez vos photos')
        	 ->setAttribute('id', 'pictures')
        	 ->setAttribute('multiple', true);
    	
    	$submit = new Element\Submit('submit');
    	$submit->setValue('Importer')
    	       ->setAttribute('class', 'btn btn-primary');
    	
    	$this->add($select);
    	$this->add($file);
    	$this->add($submit);
    }
    
    public function addInputFilter() {
    	$inputFilter = new InputFilter\InputFilter();
    
    	$fileInput = new InputFilter\FileInput('pictures');
    	$fileInput->setRequired(true);
    
    	$fileInput->getValidatorChain()
    	          ->attachByName('filesize',      array('min' => 1))
            	  ->attachByName('filemimetype',  array('mimeType' => 'image/jpeg', 'image/pjpeg', 'image/bmp', 'image/gif' ,'image/png'));
    
    	$inputFilter->add($fileInput);
    
    	$this->setInputFilter($inputFilter);
    }
}