<?php

namespace Site\Models\Business;

use Site\Models\Business\Contact\ContactSendMail;
use Site\Models\Business\Contact\ContactUploadFile;

class Contact
{

    private $_entity;

    public function __construct ()
    {
        $this->_entity = new \Site\Models\DataAccess\Entity\Contact;

    }

    public function setName ( $name )
    {
        $this->_entity->setName($name);

    }

    public function setEmail ( $email )
    {
        $this->_entity->setEmail($email);

    }

    public function setPhone ( $phone )
    {
        $this->_entity->setPhone($phone);

    }

    public function setMessage ( $message )
    {
        $this->_entity->setMessage($message);

    }

    public function setFile ( $file )
    {
        $filename = null;

        if ( count($file) > 0 ) {
            $upload = new ContactUploadFile($file);
            $filename = URL . '/system/uploads/resume/' . $upload->upload();
        }
        $this->_entity->setFile($filename);

    }

    public function send ()
    {
        $sendmail = new ContactSendMail($this->_entity);
        $sendmail->setReceiver($this->getReceiver());
        $sendmail->send();

    }

    public function getReceiver ()
    {
        $receiver = null;
        $settings = new \Site\Models\DataAccess\Find\Settings\Settings;
        $result = $settings->getAll();

        if ( count($result) > 0 ) {

            foreach ( $result as $r ) {
                $receiver = $r->getContactEmail();
            }
        }
        return $receiver;

    }

}
