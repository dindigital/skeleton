<?php

namespace src\app\admin\models\essential;

use Din\DataAccessLayer\Select;
use Din\Mvc\View\View;
use Din\Email\Email;
use Din\Email\SendEmail\SendEmail;
use src\app\admin\validators\StringValidator;
use src\app\admin\validators\DBValidator;
use Din\Exception\JsonException;
use src\app\admin\custom_filter\TableFilterAdm as TableFilter;

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

  public function recover_password ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredEmail('email', "E-mail");
    //
    $db_validator = new DBValidator($input, $this->_dao, 'admin');
    $db_validator->validateRequiredRecord('email', "E-mail");
    //
    JsonException::throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->timestamp()->filter('password_change_date');
    //
    $this->_dao->update($this->_table, array(
        'email = ?' => $input['email']
    ));

    $this->send_email($input['email']);
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

  public function update_password ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('password', 'Senha');
    $str_validator->validateRequiredString('password2', 'Confirmação de Senha');
    $str_validator->validateEqualValues('password', 'password2', 'Senha');
    //
    $this->validateToken($input['token']);
    //
    JsonException::throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->crypted()->filter('password');
    $f->null()->filter('password_change_date');
    //

    $this->_dao->update($this->_table, array(
        'id_admin = ?' => $input['token']
    ));
  }

  protected function send_email ( $user_email )
  {
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

    $sendmail = new SendEmail($email);
    $sendmail->setHost(SMTP_HOST);
    $sendmail->setUser(SMTP_USER);
    $sendmail->setPass(SMTP_PASS);
    $sendmail->send();
  }

  protected function validateToken ( $id )
  {
    $select = new Select('admin');
    $select->addField('password_change_date');
    $select->where(array('id_admin = ?' => $id));
    $result = $this->_dao->select($select);

    if ( !count($result) )
      return JsonException::addException('Token inválido, por favor gere um novo link de recuperação');

    $time_inicial = strtotime(date('Y-m-d'));
    $time_final = strtotime($result[0]['password_change_date']);

    $diferenca = $time_final - $time_inicial;
    $dias = (int) floor($diferenca / (60 * 60 * 24));

    if ( $dias !== 0 )
      return JsonException::addException('Token expirado, por favor gere um novo link de recuperação');
  }

}
