<?php

namespace src\app\adm\models;

use src\app\adm\models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\tables\ConfiguracaoTable;
use Din\Validation\Validate;

/**
 *
 * @package app.models
 */
class ConfiguracaoModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->_table = new ConfiguracaoTable;
  }

  public function setTitleHome ( $title_home )
  {
    if ( $title_home == '' )
      throw new \Exception('Title Home é obrigatório');

    $this->_table->title_home = $title_home;
  }

  public function setDescriptionHome ( $description_home )
  {
    if ( $description_home == '' )
      throw new \Exception('Description Home é obrigatório');

    $this->_table->description_home = $description_home;
  }

  public function setKeywordsHome ( $keywords_home )
  {
    if ( $keywords_home == '' )
      throw new \Exception('Keywords Home é obrigatório');

    $this->_table->keywords_home = $keywords_home;
  }

  public function setTitleInterna ( $title_interna )
  {
    if ( $title_interna == '' )
      throw new \Exception('Title Internas é obrigatório');

    $this->_table->title_interna = $title_interna;
  }

  public function setDescriptionInterna ( $description_interna )
  {
    if ( $description_interna == '' )
      throw new \Exception('Description Internas é obrigatório');

    $this->_table->description_interna = $description_interna;
  }

  public function setKeywordsInterna ( $keywords_interna )
  {
    if ( $keywords_interna == '' )
      throw new \Exception('Keywords Internas é obrigatório');

    $this->_table->keywords_interna = $keywords_interna;
  }

  public function setQtdHoras ( $qtd_horas )
  {
    $qtd_horas = intval($qtd_horas);

    if ( $qtd_horas < 0 )
      throw new \Exception('Qtd. hora deve ser um número maior ou igual a 0');

    $this->_table->qtd_horas = $qtd_horas;
  }

  public function setEmailAvisos ( $email_avisos )
  {
    if ( !Validate::email($email_avisos) )
      throw new \Exception('E-mail avisos deve ser um e-mail válido');

    $this->_table->email_avisos = $email_avisos;
  }

  public function getById ( $id_configuracao )
  {
    $select = new Select('configuracao');
    $select->addField('*');
    $select->where(array(
        'id_configuracao = ?' => $id_configuracao
    ));
    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new \Exception('Configuração não encontrada.');

    return $result[0];
  }

  public function atualizar ( $id, $info )
  {
    $this->setTitleHome($info['title_home']);
    $this->setDescriptionHome($info['description_home']);
    $this->setKeywordsHome($info['keywords_home']);
    $this->setTitleInterna($info['title_interna']);
    $this->setDescriptionInterna($info['description_interna']);
    $this->setKeywordsInterna($info['keywords_interna']);
    $this->setQtdHoras($info['qtd_horas']);
    $this->setEmailAvisos($info['email_avisos']);

    $this->_dao->update($this->_table, $id);
  }

}
