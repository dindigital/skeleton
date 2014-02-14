<?php

namespace src\app\admin\models\essential;

use Din\DataAccessLayer\Select;
use src\app\admin\validators\AdminPasswordValidator as validator;
use Din\Mvc\View\View;
use Din\Email\Email;
use Din\Email\SendEmail\SendEmail;

/**
 *
 * @package app.models
 */
class AdminPasswordModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('admin');
  }

  public function recover_password ( $email )
  {
    $this->_table->password_change_date = date('Y-m-d');

    $validator = new validator($this->_table);
    $validator->setInput(array('email' => $email));
    $validator->setDao($this->_dao);
    $validator->setEmail('email', 'E-mail');
    $validator->requireRecord('email', 'E-mail');
    $validator->throwException();

    // Atualiza o campo senha_data para a data atual, com isso conseguiremos posteriormente
    // verificar se a recuperação de senha ainda é válida
    $this->_dao->update($this->_table, array('email = ?' => $email));

    $this->send_email();
  }

  protected function getTokenByEmail ( $email )
  {
    // Retorno o ID do banco de dados
    $select = new Select('admin');
    $select->addField('id_admin');
    $select->where(array('email = ?' => $email));
    $result = $this->_dao->select($select);

    return $result[0]['id_admin'];
  }

  public function update_password ( $data )
  {
    $this->_table->password_change_date = null;

    $validator = new validator($this->_table);
    $validator->setInput($data);
    $validator->setDao($this->_dao);
    $validator->validateToken($data['token']);
    $validator->setEqualValues('password', 'password2', 'Senha');
    $validator->setPassword('password', 'Senha');
    $validator->throwException();

    $this->_dao->update($this->_table, array('id_admin = ?' => $data['token']));
  }

  private function send_email ()
  {
    $user_email = $this->_table->email;
    $token = $this->getTokenByEmail($user_email);

    $data = array(
        'link' => URL . '/admin/admin_password/update/' . $token . '/'
    );

    $email_html = new View;
    $email_html->addFile('src/app/admin/views/email/recover_password.phtml');
    $email_html->setData($data);

    $email = new Email;
    $email->setFrom('suporte@dindigital.com');
    $email->setTo($user_email);
    $email->setSubject('Recuperação de Senha - Painel de Controle');
    $email->setBody($email_html->getResult());

    die($email->getBody());

    $sendmail = new SendEmail($email);
    $sendmail->setHost(SMTP_HOST);
    $sendmail->setUser(SMTP_USER);
    $sendmail->setPass(SMTP_PASS);
    $sendmail->send();
  }

}
