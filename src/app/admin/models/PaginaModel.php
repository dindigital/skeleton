<?php

namespace src\app\admin\models;

use src\app\admin\validators\PaginaValidator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;
use src\app\admin\helpers\Ordem;

/**
 *
 * @package app.models
 */
class PaginaModel extends BaseModelAdm
{

  public function listar ( $arrFilters = array(), Paginator $paginator = null )
  {
    $arrCriteria = array(
        'a.del = ?' => '0',
        'a.titulo LIKE ?' => '%' . $arrFilters['titulo'] . '%'
    );

    if ( $arrFilters['id_pagina_cat'] != '' && $arrFilters['id_pagina_cat'] != '0' ) {
      $arrCriteria['a.id_pagina_cat = ?'] = $arrFilters['id_pagina_cat'];
    }

    $select = new Select('pagina');
    $select->addField('id_pagina');
    $select->addField('ativo');
    $select->addField('titulo');
    $select->addField('inc_data');
    $select->addField('ordem');
    $select->where($arrCriteria);
    $select->order_by('a.ordem=0,a.ordem,a.titulo');

    $select->inner_join('id_pagina_cat', Select::construct('pagina_cat')
                    ->addField('titulo', 'menu'));

    $result = $this->_dao->select($select);
    $result = Ordem::setDropdown($this, $result, $arrCriteria);

    return $result;
  }

  public function inserir ( $info )
  {
    $validator = new PaginaValidator();
    $id = $validator->setId($this);
    $validator->setIdPaginaCat($info['id_pagina_cat']);
    $validator->setIdParent($info['id_parent']);
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
    $validator = new PaginaValidator();
    $validator->setIdPaginaCat($info['id_pagina_cat']);
    $validator->setIdParent($info['id_parent']);
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setConteudo($info['conteudo']);
    $validator->setDescription($info['description']);
    $validator->setKeywords($info['keywords']);
    $validator->setArquivo('capa', $info['capa'], $id);
    $validator->throwException();

    $tableHistory = $this->getById($id);
    $this->_dao->update($validator->getTable(), array('id_pagina = ?' => $id));
    $this->log('U', $info['titulo'], $validator->getTable(), $tableHistory);

    return $id;
  }

  public function getDropdown ( $id_pagina_cat = null, $id_parent = '', $exclude_id = null )
  {
    $select = new Select('pagina');
    $select->addField('id_pagina');
    $select->addField('titulo');
    $arrCriteria = array(
        'del = ? ' => '0'
    );
    if ( $id_pagina_cat ) {
      $arrCriteria['id_pagina_cat = ?'] = $id_pagina_cat;
    }
    if ( $exclude_id ) {
      $arrCriteria['id_pagina <> ?'] = $exclude_id;
    }

    if ( $id_parent !== '' ) {
      if ( is_null($id_parent) ) {
        $arrCriteria['id_parent IS NULL'] = null;
      } else {
        $arrCriteria['id_parent = ?'] = $id_parent;
      }
    }
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    $arrOptions = array();
    foreach ( $result as $row ) {
      $arrOptions[$row['id_pagina']] = $row['titulo'];
    }

    return $arrOptions;
  }

  public function getById ( $id )
  {
    $row = parent::getById($id);
    $row['infinito'] = $this->loadInfinity($id);

    return $row;
  }

  public function loadInfinity ( $id )
  {
    $r = array();

    $first = parent::getById($id);
    $id_cat = $first['id_pagina_cat'];
    if ( $first['id_parent'] ) {

      $last = array(
          'dropdown' => $this->getDropdown($id_cat, $first['id_parent'], $first['id_pagina']),
          'selected' => null
      );

      while ($first['id_parent']) {
        $second = parent::getById($first['id_parent']);
        $r[] = array(
            'dropdown' => $this->getDropdown($id_cat, $second['id_parent']),
            'selected' => $first['id_parent']
        );

        $first = $second;
      }

      $r = array_reverse($r);

      $r[] = $last;
    }

    return $r;
  }

}
