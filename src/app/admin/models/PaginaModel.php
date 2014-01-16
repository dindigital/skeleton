<?php

namespace src\app\admin\models;

use src\app\admin\validators\PaginaValidator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;
use src\app\admin\helpers\Ordem;
use Din\Form\Dropdown\Dropdown;

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
    $id = $validator->setIdPagina()->getTable()->id_pagina;
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

  public function getDropdown ( $firstOption = '', $selected = null, $class = null, $id_pagina_cat = null, $id_parent = '', $exclude_id = null )
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

    $d = new Dropdown('id_parent[]');
    $d->setOptionsResult($result, 'id_pagina', 'titulo');
    $d->setClass('form-control');
    if ( $class ) {
      $d->setClass('form-control ' . $class);
    }
    $d->setSelected($selected);
    if ( $firstOption != '' ) {
      $d->setFirstOpt($firstOption);
    }

    return $d->getElement();
  }

  public function loadInfinity ( $id )
  {
    $r = array();

    $first = $this->getById($id);
    $id_cat = $first['id_pagina_cat'];
    if ( $first['id_parent'] ) {

      $last = $this->getDropdown('Subnível de Página', null, 'ajax_pagina_infinita', $id_cat, $first['id_parent'], $first['id_pagina']);

      while ($first['id_parent']) {
        $second = $this->getById($first['id_parent']);
        $r[] = $this->getDropdown('Subnível de Página', $first['id_parent'], 'ajax_pagina_infinita', $id_cat, $second['id_parent']);
        $first = $second;
      }

      $r = array_reverse($r);

      $r[] = $last;
    }

    return $r;
  }

}
