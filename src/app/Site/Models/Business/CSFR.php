<?php

namespace Site\Models\Business;

class CSFR
{

    protected $_prefix = 'B3a@!#b9mE0L3z&%-Gf6U@=pC8m$';

    public function __construct ()
    {
        //verifica se o session start foi iniciado
        if ( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }

    }

    //gera um valor dinamico para o form, assim evita que alguem tente fazer brute force e csfr
    public function generate_token ( $key, $time = 1800 )
    {
        if ( !isset($_SESSION['csrf_' . $key]) ) {
            $token = $this->create_token();
            $this->create_session($key, $token);
        } else if ( !isset($_SESSION['csrf_start_time' . $key]) || (time() - $_SESSION['csrf_start_time' . $key] > $time ) ) {
            $token = $this->create_token();
            $this->create_session($key, $token);
        } else {
            $token = $_SESSION['csrf_' . $key];
        }

        //retorna o token
        return $token;

    }

    //cria o token
    protected function create_token ()
    {
        $token = $this->_prefix . base64_encode(openssl_random_pseudo_bytes(32)) . sha1($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']) . microtime() . md5(uniqid(rand(), true));
        $token = sha1(md5($token));
        return $token;

    }

    //cria a session
    protected function create_session ( $key, $token )
    {
        //invalida o token da session anterior
        session_regenerate_id(true);

        //gera uma session com o token
        $_SESSION['csrf_' . $key] = $token;

        //guarda o time de quando o token foi gerado
        $_SESSION['csrf_start_time_' . $key] = time();

    }

    //verifica se o token e valido
    public function validate_token ( $key, $post_value, $time = 1800 )
    {
        if ( !isset($_SESSION['csrf_' . $key]) ) {
            return false;
        } else if ( !isset($_SESSION['csrf_start_time_' . $key]) ) {
            return false;
        } else if ( (time() - $_SESSION['csrf_start_time_' . $key] > $time ) ) {
            return false;
        } else if ( $_SESSION['csrf_' . $key] !== $post_value ) {
            return false;
        } else {
            return true;
        }

    }

}
