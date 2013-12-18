<?php

namespace src\app\adm\models;

use src\app\adm\models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\adm\validators\ConfiguracaoValidator;
use \Exception;

/**
 *
 * @package app.models
 */
class ConfiguracaoModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
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
      throw new Exception('Configuração não encontrada.');

    return $result[0];
  }

  public function atualizar ( $id, $info )
  {
    $validator = new ConfiguracaoValidator;
    $validator->setTitleHome($info['title_home']);
    $validator->setDescriptionHome($info['description_home']);
    $validator->setKeywordsHome($info['keywords_home']);
    $validator->setTitleInterna($info['title_interna']);
    $validator->setDescriptionInterna($info['description_interna']);
    $validator->setKeywordsInterna($info['keywords_interna']);
    $validator->setQtdHoras($info['qtd_horas']);
    $validator->setEmailAvisos($info['email_avisos']);

    $this->_dao->update($validator->getTable(), array('id_configuracao = ?' => '1'));
  }

}
