<?php

namespace Site\Models\DataAccess\Save;

class ContactSave extends AbstractSave
{

    public function __construct()
    {
        parent::__construct('contact');
        $this->_table->id_contact = $this->getNewId();

    }

    public function setName()
    {
        $this->_table->name = $this->_entity->getName();

    }

    public function setEmail()
    {
        $this->_table->email = $this->_entity->getEmail();

    }

    public function setCnpj()
    {
        $this->_table->cnpj = $this->_entity->getCnpj();

    }

    public function setLocation()
    {
        $this->_table->location = $this->_entity->getLocation();

    }

    public function setPhone()
    {
        $this->_table->phone = $this->_entity->getPhone();

    }

    public function setMessage()
    {
        $this->_table->message = $this->_entity->getMessage();

    }

}
