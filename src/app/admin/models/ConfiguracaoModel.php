<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\validators\ConfiguracaoValidator;
use Din\Exception\JsonException;

/**
 *
 * @package app.models
 */
class ConfiguracaoModel extends BaseModelAdm
{

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
    $validator->throwException();

    $tableHistory = $this->getById($id);
    $this->_dao->update($validator->getTable(), array('id_configuracao = ?' => '1'));
    $this->log('U', 'Configurações', $validator->getTable(), $tableHistory);
  }

}
