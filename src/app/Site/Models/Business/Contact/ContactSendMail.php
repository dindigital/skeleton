<?php

namespace Site\Models\Business\Contact;

use Site\Models\DataAccess\Entity\Contact;
use Din\Email\Email;
use Din\Email\SendEmail\SendEmail;
use Exception;
use Twig_Loader_Filesystem;
use Twig_Environment;

class ContactSendMail
{

    private $entity;
    private $receiver;

    public function __construct ( Contact $contact )
    {
        $this->entity = $contact;

    }

    public function setReceiver ( $receiver )
    {
        if ( is_null($receiver) || $receiver == '' ) {
            throw new Exception("E-mail destinatário não está definido no painel de controle", 1);
        }

        $this->receiver = $receiver;

    }

    public function send ()
    {

        $entity['entity'] = $this->entity;
        $loader = new Twig_Loader_Filesystem('src/app/Site/Views/email');
        $this->_twig = new Twig_Environment($loader);
        $html = $this->_twig->render('contact.html', $entity);

        $email = new Email;
        $email->setFrom($this->entity->getEmail(), $this->entity->getName());
        $email->setTo($this->receiver);
        $email->setSubject('Formulário de Contato');
        $email->setBody($html);

        $sendmail = new SendEmail($email);
        $sendmail->setHost(SMTP_HOST);
        $sendmail->setUser(SMTP_USER);
        $sendmail->setPass(SMTP_PASS);
        $sendmail->send();

    }

}
