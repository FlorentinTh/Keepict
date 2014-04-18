<?php
namespace Keepict\src\Keepict\Classes;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class Mailer
{
    private $NAME = 'localhost.localdomain';
    private $HOST = 'smtp.gmail.com';
    private $PORT = 587;
    private $USERNAME = 'thullierflorentin';
    private $PASSWORD = 'CTfJLrWyu8tXoNDJQVa2hoLnHeAnpg';
    
    public function __contruct(){}
    
    public function sendCourriel($to, $subject, $body){
        $message = new Message();
        $message->addFrom('contact@keepict.fr', 'Contact Keepict')
                ->addTo($to)
                ->setSubject($subject)
                ->setBody($body);
        $transport = new SmtpTransport();
        $options   = new SmtpOptions(array(
        		'name' => $this->NAME,
        		'host' => $this->HOST,
        		'port' => $this->PORT,
        		'connection_class' => 'login',
        		'connection_config' => array(
        				'username' => $this->USERNAME,
        				'password' => $this->PASSWORD,
        				'ssl' => 'tls'
        		),
        ));
        $transport->setOptions($options);
        $transport->send($message);
    }
}

?>