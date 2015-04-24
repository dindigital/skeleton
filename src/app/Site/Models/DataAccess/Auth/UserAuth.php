<?php

namespace Site\Models\DataAccess\Auth;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select;
use Din\Crypt\Crypt;

class UserAuth extends AbstractFind
{

    protected $_id_customer;

    public function is_valid ( $email, $password )
    {
        $select = new Select('customer');
        $select->addField('id_customer');

        $crypt = new Crypt;
        $senha = $crypt->crypt($password);

        $select->where(array(
            'email = ?' => $email,
            'password = ?' => $senha,
        ));


        $result = $this->_dao->select($select);
        $count = count($result);

        if ( $count ) {
            $this->_id_customer = $result[0]['id_customer'];
        }

        return $count;

    }

    public function getUserId ()
    {
        return $this->_id_customer;

    }

}
