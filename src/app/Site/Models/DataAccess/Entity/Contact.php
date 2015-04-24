<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Contact extends AbstractEntity
{

    /**
     *
     * @param type $value
     * @throws \Exception
     */
    public function setName ( $value )
    {
        if ( $value == '' )
            throw new \Exception('Campo nome é obrigatório');

        $this->setField('name', $value);

    }

    public function getName ()
    {
        return $this->getField('name');

    }

    /**
     *
     * @param type $value
     * @throws \Exception
     */
    public function setEmail ( $value )
    {
        if ( $value == '' )
            throw new \Exception('Campo e-mail é obrigatório');

        $this->setField('email', $value);

    }

    public function getEmail ()
    {
        return $this->getField('email');

    }

    /**
     *
     * @param type $value
     * @throws \Exception
     */
    public function setPhone ( $value )
    {
        if ( $value == '' )
            throw new \Exception('Campo telefone é obrigatório');

        $this->setField('phone', $value);

    }

    public function getPhone ()
    {
        return $this->getField('phone');

    }

    /**
     *
     * @param type $value
     * @throws \Exception
     */
    public function setMessage ( $value )
    {
        if ( $value == '' )
            throw new \Exception('Campo mensagem é obrigatório');

        $this->setField('message', $value);

    }

    public function getMessage ()
    {
        return $this->getField('message');

    }

    public function setFile ( $value )
    {
        $this->setField('file', $value);

    }

    public function getFile ()
    {
        return $this->getField('file');

    }

}
