<?php

namespace Site\Models\DataAccess\Auth;

use Din\Session\Session;

class UserAuthSession
{

    protected $_session;

    public function __construct ()
    {
        $this->_session = new Session('site_auth');

    }

    public function startSession ( $user_id )
    {
        $this->_session->set('user_id', $user_id);

    }

    public function isLogged ()
    {
        return $this->_session->is_set('user_id');

    }

    public function getUserId ()
    {
        if ( $this->isLogged() )
            return $this->_session->get('user_id');

    }

    public function logout ()
    {
        $this->_session->un_set('user_id');

    }

}
