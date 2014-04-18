<?php

namespace Classes;

use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;

class SessionUtils{
    public static function checkUserAllowed(){
        $auth = new AuthenticationService();
        
        $identity = null;
        $logged = null;
        
        if ($auth->hasIdentity()){
        	$identity = $auth->getIdentity();
        	$session = new Container('user');
        	$session->offsetUnset('courriel');
        	$session->offsetSet('courriel',$identity);
        	$logged = $session->offsetGet('courriel');
        }else{
        	$auth = new AuthenticationService();
        	$auth->clearIdentity();
        	$session = new Container('user');
        	$session->offsetUnset('courriel');
        }
        return $logged;
    }
}