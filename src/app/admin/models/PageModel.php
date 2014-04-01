<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\MoveFiles;
use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;
use src\app\admin\validators\StringValidator;
use src\app\admin\validators\UploadValidator;
use src\app\admin\validators\DBValidator;
use Din\Exception\JsonException;
use src\app\admin\filters\TableFilter;
use Exception;
use src\app\admin\filters\SequenceFilter;
use src\app\admin\helpers\SequenceResult;

/**
 *
 * @package app.models
 */
class PageModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('page');
  }

  public function formatTable ( $table, $exclude_upload = false )
  {
    if ( $exclude_upload ) {
      $table['cover'] = null;
      $table['uri'] = null;
    }

    $page_cat = new PageCatModel;
    $page_cat_dropdown = $page_cat->getListArray();

    $table['title'] = Html::scape($table['title']);
    $table['content'] = Form::Ck('content', $table['content']);
    $table['cover_uploader'] = Form::Upload('cover', $table['cover'], 'image');
    $table['uri'] = Link::formatNavUri($table['uri'], true);
    $table['id_page_cat'] = Form::Dropdown('id_page_cat', $page_cat_dropdown, $table['id_page_cat'], 'Selecione um Menu', null, 'ajax_intinify_cat');

    $table['id_parent'] = $this->loadInfinity();

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

    $seq = new SequenceResult($this->_entity, $this->_dao);
    $result = $seq->filterResult($result, $arrCriteria);
    //

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
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('title', "Título");
    //
    $upl_validator = new UploadValidator($input);
    $has_cover = $upl_validator->validateFile('cover');
    //
    $db_validator = new DBValidator($input, $this->_dao, 'page');
    $db_validator->validateFk('id_page_cat', 'Menu', 'page_cat');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setNewId('id_page');
    $filter->setTimestamp('inc_date');
    $filter->setIntval('active');
    $filter->setString('id_page_cat');
    $filter->setIdParent();
    $filter->setString('title');
    $filter->setString('content');
    $filter->setString('description');
    $filter->setString('keywords');
    $page_cat = new PageCatModel;
    $filter->setNavUri('title', $page_cat->getTitle($this->_table->id_page_cat));
    //
    $seq_filter = new SequenceFilter($this->_table, $this->_dao, $this->_entity);
    $seq_filter->setSequence();
    //
    $mf = new MoveFiles;
    $filter->setUploaded('cover', "/system/uploads/page/{$this->getId()}/cover", $has_cover, $mf);
    //
    $mf->move();

    $this->dao_insert();
  }

  public function update ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('title', "Título");
    //
    $upl_validator = new UploadValidator($input);
    $has_cover = $upl_validator->validateFile('cover');
    //
    $db_validator = new DBValidator($input, $this->_dao, 'page');
    $db_validator->validateFk('id_page_cat', 'Menu', 'page_cat');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setIntval('active');
    $filter->setString('id_page_cat');
    $filter->setIdParent();
    $filter->setString('title');
    $filter->setString('content');
    $filter->setString('description');
    $filter->setString('keywords');
    $page_cat = new PageCatModel;
    $filter->setNavUri('title', $page_cat->getTitle($this->_table->id_page_cat));
    //
    $mf = new MoveFiles;
    $filter->setUploaded('cover', "/system/uploads/page/{$this->getId()}/cover", $has_cover, $mf);
    //
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

  public function getListArray ( $id_page_cat = null, $id_parent = '', $exclude_id = '' )
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
    if ( $exclude_id != '' ) {
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

    try {
      $first = $this->getInfinityMembers();
    } catch (Exception $e) {
      return null;
    }

    $id_cat = $first['id_page_cat'];

    $r[] = array(
        'dropdown' => $this->getListArray($id_cat, $first['id_parent'], $first['id_page']),
        'selected' => null
    );

    if ( $first['id_parent'] ) {

      while ($first['id_parent']) {
        $second = $this->getInfinityMembers($first['id_parent']);
        $r[] = array(
            'dropdown' => $this->getListArray($id_cat, $second['id_parent']),
            'selected' => $first['id_parent']
        );

        $first = $second;
      }

      $r = array_reverse($r);
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
