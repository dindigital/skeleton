<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\MoveFiles;
use src\app\admin\models\essential\SequenceModel;
use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;

/**
 *
 * @package app.models
 */
class PageModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('page');
  }

  public function formatTable ( $table )
  {

    $page_cat = new PageCatModel;
    $page_cat_dropdown = $page_cat->getListArray();

    $table['title'] = Html::scape($table['title']);
    $table['content'] = Form::Ck('content', $table['content']);
    $table['cover_uploader'] = Form::Upload('cover', $table['cover'], 'image');
    $table['uri'] = Link::formatUri($table['uri']);
    $table['id_page_cat'] = Form::Dropdown('id_page_cat', $page_cat_dropdown, $table['id_page_cat'], 'Selecione um Menu', null, 'ajax_intinify_cat');

    if ( isset($table['id_parent']) && $table['id_parent'] ) {
      $table['id_parent'] = $this->loadInfinity();
    }

    $infinite_drop = array();
    foreach ( (array) $table['id_parent'] as $i => $drop ) {
      $addClass = 'other';
      $infinite_drop[] = $this->formatInfiniteDropdown($drop['dropdown'], $drop['selected'], $addClass);
    }

    $table['id_parent'] = $infinite_drop;

    return $table;
  }

  public function formatInfiniteDropdown ( $dropdown, $selected = null )
  {
    $dropdown = Form::Dropdown('id_parent[]', $dropdown, $selected, 'Subnível de Página', null, 'ajax_infinity');

    return $dropdown;
  }

  public function getList ()
  {
    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.title LIKE ?' => '%' . $this->_filters['title'] . '%'
    );

    if ( $this->_filters['id_page_cat'] != '' && $this->_filters['id_page_cat'] != '0' ) {
      $arrCriteria['a.id_page_cat = ?'] = $this->_filters['id_page_cat'];
    }

    $select = new Select('page');
    $select->addField('id_page');
    $select->addField('active');
    $select->addField('title');
    $select->addField('inc_date');
    $select->addField('sequence');
    $select->addField('uri');
    $select->where($arrCriteria);
    $select->order_by('a.sequence=0,a.sequence,a.title');

    $select->inner_join('id_page_cat', Select::construct('page_cat')
                    ->addField('title', 'menu'));

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);
    $seq = new SequenceModel($this);
    $result = $seq->setListArray($result, $arrCriteria);

    foreach ( $result as $i => $row ) {
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date']);
      if ( isset($row['sequence_list_array']) ) {
        $result[$i]['sequence'] = Form::Dropdown('sequence', $row['sequence_list_array'], $row['sequence'], '', $row['id_page'], 'drop_sequence');
      }
    }

    return $result;
  }

  public function insert ( $input )
  {
    $this->setNewId();
    $this->setTimestamp('inc_date');
    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['title'], 'page');
    $this->_table->content = $input['content'];
    $this->_table->description = $input['description'];
    $this->_table->keywords = $input['keywords'];

    $validator = new validator($this->_table);
    $validator->setDao($this->_dao);
    $validator->setInput($input);
    $validator->setId($this->getId());
    $validator->setFk('id_page_cat', 'Menu', 'page_cat');
    $validator->setIdParent($input['id_parent']);
    $validator->setRequiredString('title', 'Título');

    $mf = new MoveFiles;
    $validator->setFile('cover', $mf);
    $validator->throwException();



    $seq = new SequenceModel($this);
    $seq->setSequence();

    $mf->move();

    $this->dao_insert();
  }

  public function update ( $input )
  {
    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['title'], 'page', $input['uri']);
    $this->_table->content = $input['content'];
    $this->_table->description = $input['description'];
    $this->_table->keywords = $input['keywords'];

    $validator = new validator($this->_table);
    $validator->setDao($this->_dao);
    $validator->setInput($input);
    $validator->setId($this->getId());
    $validator->setFk('id_page_cat', 'Menu', 'page_cat');
    $validator->setIdParent($input['id_parent']);
    $validator->setRequiredString('title', 'Título');

    $mf = new MoveFiles;
    $validator->setFile('cover', $mf);
    $validator->throwException();

    // deleta o arquivo antigo caso exista e tenha upload novo
    $row = $this->getById();
    if ( $this->_table->cover && $row['cover'] ) {
      $destiny = 'public/' . $row['cover'];
      @unlink($destiny);
    }

    $mf->move();

    $this->dao_update();
  }

  public function formatFilters ()
  {
    $page_cat = new PageCatModel;
    $page_cat_dropdown = $page_cat->getListArray();

    $this->_filters['title'] = Html::scape($this->_filters['title']);
    $this->_filters['id_page_cat'] = Form::Dropdown('id_page_cat', $page_cat_dropdown, $this->_filters['id_page_cat'], 'Filtro por Menu');

    return $this->_filters;
  }

  public function getListArray ( $id_page_cat = null, $id_parent = '', $exclude_id = null )
  {
    $select = new Select('page');
    $select->addField('id_page');
    $select->addField('title');
    $arrCriteria = array(
        'is_del = ? ' => '0'
    );
    if ( $id_page_cat ) {
      $arrCriteria['id_page_cat = ?'] = $id_page_cat;
    }
    if ( $exclude_id ) {
      $arrCriteria['id_page <> ?'] = $exclude_id;
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
      $arrOptions[$row['id_page']] = $row['title'];
    }

    return $arrOptions;
  }

  public function loadInfinity ()
  {
    $r = array();

    $first = $this->getInfinityMembers();
    $id_cat = $first['id_page_cat'];
    if ( $first['id_parent'] ) {

      $last = array(
          'dropdown' => $this->getListArray($id_cat, $first['id_parent'], $first['id_page']),
          'selected' => null
      );

      while ($first['id_parent']) {
        $second = $this->getInfinityMembers($first['id_parent']);
        $r[] = array(
            'dropdown' => $this->getListArray($id_cat, $second['id_parent']),
            'selected' => $first['id_parent']
        );

        $first = $second;
      }

      $r = array_reverse($r);

      $r[] = $last;
    }

    return $r;
  }

  protected function getInfinityMembers ( $id = null )
  {
    if ( $id ) {
      $this->setId($id);
    }

    $arrCriteria = array(
        'id_page = ?' => $this->getId()
    );

    $select = new Select('page');
    $select->addField('id_page_cat');
    $select->addField('id_page');
    $select->addField('id_parent');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado.');

    return $result[0];
  }

}
