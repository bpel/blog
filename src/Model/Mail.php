<?php

namespace src\Model;

use Core\Model\Model;
use PHPMailer\PHPMailer\Exception;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Mail extends Model
{

    private $_subject;
    private $_receiverMail;
    private $_text;
    private $_mailer;
    private $_transport;

    public function __construct()
    {
        $this->_transport = (new Swift_SmtpTransport($this->getEnvVar('mail_host'), $this->getEnvVar('mail_port')))
            ->setUsername($this->getEnvVar('mail_username'))
            ->setPassword($this->getEnvVar('mail_password'))
        ;
        $this->_mailer = new Swift_Mailer($this->_transport);
    }

    public function setMessageMail($text)
    {
        $this->_text = $text;
    }

    public function setReceiverMail($receiverMail)
    {
        $this->_receiverMail = $receiverMail;
    }

    public function setSubjectMail($subject)
    {
        $this->_subject = $subject;
    }

    public function sendMail()
    {
        $message = (new Swift_Message($this->_subject))
            ->setFrom([$this->getEnvVar('mail_receiver') => $this->getEnvVar('full_name')])
            ->setTo($this->_receiverMail)
            ->setBody($this->_text)
        ;

        $this->_mailer->send($message,$failures);
    }

}