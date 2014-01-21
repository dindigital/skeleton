<?php

namespace src\app\admin\models;

use src\app\admin\validators\PaginaCatValidator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;
use Din\Form\Dropdown\Dropdown;
use src\app\admin\helpers\Ordem;

/**
 *
 * @package app.models
 */
class PaginaCatModel extends BaseModelAdm
{

  public function listar ( $arrFilters = array(), Paginator $paginator = null )
  {
    $arrCriteria = array(
        'del = ?' => '0',
        'titulo LIKE ?' => '%' . $arrFilters['titulo'] . '%'
    );

    $select = new Select('pagina_cat');
    $select->addField('id_pagina_cat');
    $select->addField('ativo');
    $select->addField('titulo');
    $select->addField('inc_data');
    $select->addField('ordem');
    $select->where($arrCriteria);
    $select->order_by('ordem');

    $result = $this->_dao->select($select);
    $result = Ordem::setDropdown($this, $result, $arrCriteria);

    return $result;
  }

  public function inserir ( $info )
  {
    $validator = new PaginaCatValidator();
    $id = $validator->setId($this);
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setConteudo($info['conteudo']);
    $validator->setDescription($info['description']);
    $validator->setKeywords($info['keywords']);
    $validator->setIncData();
    $validator->setArquivo('capa', $info['capa'], $id);

    Ordem::setOrdem($this, $validator);
    $validator->throwException();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['titulo'], $validator->getTable());

    return $id;
  }

  public function atualizar ( $id, $info )
  {
    $validator = new PaginaCatValidator();
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setConteudo($info['conteudo']);
    $validator->setDescription($info['description']);
    $validator->setKeywords($info['keywords']);
    $validator->setArquivo('capa', $info['capa'], $id);
    $validator->throwException();

    $tableHistory = $this->getById($id);
    $this->_dao->update($validator->getTable(), array('id_pagina_cat = ?' => $id));
    $this->log('U', $info['titulo'], $validator->getTable(), $tableHistory);

    return $id;
  }

  public function getDropdown ()
  {
    $select = new Select('pagina_cat');
    $select->addField('id_pagina_cat');
    $select->addField('titulo');
    $select->where(array(
        'del = ? ' => '0'
    ));

    $result = $this->_dao->select($select);

    $arrOptions = array();
    foreach ( $result as $row ) {
      $arrOptions[$row['id_pagina_cat']] = $row['titulo'];
    }

    return $arrOptions;
  }

}
