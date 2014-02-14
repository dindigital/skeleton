<?php

namespace src\app\admin\models\essential;

use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\PDO\PDOBuilder;
use src\app\admin\helpers\Entities;
use Din\DataAccessLayer\Select;
use Din\File\Folder;
use Exception;
use src\app\admin\models\essential\LogMySQLModel as log;
use Din\DataAccessLayer\Table\Table;
use Din\UrlShortener\Bitly\Bitly;
use Din\Filters\String\Uri;

class BaseModelAdm
{

  protected $_dao;
  protected $_paginator = null;
  protected $_itens_per_page = 20;
  protected $_table;

  public function __construct ()
  {
    $this->_dao = new DAO(PDOBuilder::build(DB_TYPE, DB_HOST, DB_SCHEMA, DB_USER, DB_PASS));
    Entities::readFile('config/entities.php');
  }

  /*
   * ===========================================================================
   * SETTERS FOR TABLE
   * ===========================================================================
   */

  public function setTable ( $tablename )
  {
    $this->_table = new Table($tablename);
  }

  public function getTable ()
  {
    return $this->_table;
  }

  public function setNewId ()
  {
    $this->setId(md5(uniqid()));
  }

  public function getIdName ()
  {
    $entity = Entities::getThis($this);
    $property = $entity['id'];

    return $property;
  }

  public function setId ( $id )
  {
    $property = $this->getIdName();
    $this->_table->{$property} = $id;
  }

  public function getId ()
  {
    $property = $this->getIdName();

    return $this->_table->{$property};
  }

  public function setIntval ( $field, $value )
  {
    $value = intval($value);

    $this->_table->{$field} = $value;
  }

  public function setTimestamp ( $field )
  {
    $this->_table->{$field} = date('Y-m-d H:i:s');
  }

  /**
   * Adiciona o valor do campo LINK na tabela, seguindo o padrão de desenvolvimento.
   * Caso haja necessidade de criação de um link diferente, criar um método setLink
   * no validator da própria tabela.
   * @param String $title - Titulo do conteúdo (obrigatório)
   * @param String $prefix - Prefixo para formar o padrão da URI, caso não adicione nada
   *                      o link ficará como "/titulo-id/". Adicionando a string:
   *                      "flores/rosas", teremos "/flores/rosas/titulo-id/"
   * @param String $link - Usado no editar, possibilita que o administrador altere
   *                       a formação do link (area do título), mantendo o
   *                       padrão (prefixo e id).
   */
  public function setDefaultUri ( $title, $prefix = '', $uri = null )
  {
    $id = substr($this->getId(), 0, 4);
    $uri = is_null($uri) || $uri == '' ? Uri::format($title) : Uri::format($uri);
    if ( $prefix != '' ) {
      $prefix = '/' . $prefix;
    }
    $this->_table->uri = "{$prefix}/{$uri}-{$id}/";
  }

  public function setShortenerLink ()
  {
    if ( URL && BITLY && $this->_table->uri ) {
      $url = URL . $this->_table->uri;
      $bitly = new Bitly(BITLY);
      $bitly->shorten($url);
      if ( $bitly->check() ) {
        $this->_table->short_link = $bitly;
      }
    }
  }

  /*
   * ===========================================================================
   * PAGED
   * ===========================================================================
   */

  public function setPaginationSelect ( $select )
  {
    $total = $this->_dao->select_count($select);
    $offset = $this->_paginator->getOffset($total);
    $select->setLimit($this->_itens_per_page, $offset);
  }

  public function getPaginator ()
  {
    return $this->_paginator;
  }

  /*
   * ===========================================================================
   * DATABASE CRUD
   * ===========================================================================
   */

  public function deleteChildren ( $tbl, $id )
  {
    $current = Entities::getEntity($tbl);
    $children = Entities::getChildren($tbl);

    foreach ( $children as $child ) {
      $select = new Select($child['tbl']);
      $select->addField($child['id'], 'id_children');
      $select->where(array(
          $current['id'] . ' = ? ' => $id
      ));
      $result = $this->_dao->select($select);

      $arr_delete = array();
      foreach ( $result as $row ) {
        $arr_delete[] = array(
            'id' => $row['id_children']
        );
      }

      $child_model = new $child['model'];
      $child_model->delete($arr_delete);
    }
  }

  public function delete ( $itens )
  {
    foreach ( $itens as $item ) {
      $current = Entities::getThis($this);

      $tableHistory = $this->getById($item['id']);
      $this->deleteChildren($current['tbl'], $item['id']);

      Folder::delete("public/system/uploads/{$current['tbl']}/{$item['id']}");
      $this->_dao->delete($current['tbl'], array($current['id'] . ' = ?' => $item['id']));
      $this->log('D', $tableHistory[$current['title']], $current['tbl'], $tableHistory);
    }
  }

  public function getById ( $id = null )
  {
    if ( $id ) {
      $this->setId($id);
    }

    $current = Entities::getThis($this);

    $arrCriteria = array(
        $current['id'] . ' = ?' => $this->getId()
    );

    $select = new Select($current['tbl']);
    $select->addField('*');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado.');

    $row = $result[0];

    return $row;
  }

  public function getNew ()
  {
    $current = Entities::getThis($this);

    $SQL = "DESCRIBE {$current['tbl']}";

    $result = $this->_dao->execute($SQL, array(), true);

    $arr_return = array();

    foreach ( $result as $row ) {
      $arr_return[$row['Field']] = $row['Default'];
    }

    return $arr_return;
  }

  public function save ( $info )
  {
    if ( !$this->getId() ) {
      $this->insert($info);
    } else {
      $this->update($info);
    }

    return $this->getId();
  }

  public function getCount ( $id )
  {
    $current = Entities::getThis($this);

    $arrCriteria = array(
        $current['id'] . ' = ?' => $id
    );

    $select = new Select($current['tbl']);
    $select->where($arrCriteria);

    $result = $this->_dao->select_count($select);

    return $result;
  }

  protected function dao_insert ( $log = true )
  {
    $this->_dao->insert($this->_table);

    if ( $log ) {
      $current = Entities::getThis($this);
      $this->log('C', $this->_table->{$current['title']}, $this->_table);
    }
  }

  public function dao_update ( $log = true )
  {
    $current = Entities::getThis($this);

    if ( $log ) {
      $tableHistory = $this->getById();
    }

    $this->_dao->update($this->_table, array("{$current['id']} = ?" => $this->getId()));

    if ( $log ) {
      $this->log('U', $this->_table->{$current['title']}, $this->_table, $tableHistory);
    }
  }

  /*
   * ===========================================================================
   * LOGGABLE
   * ===========================================================================
   */

  public function log ( $action, $msg, $table, $tableHistory = null, $entityname = null )
  {
    $adminAuth = new AdminAuthModel();
    $admin = $adminAuth->getUser();

    if ( is_null($entityname) ) {
      $entities = Entities::getThis($this);
      $entityname = $entities['name'];
    }

    log::save($this->_dao, $admin, $action, $msg, $entityname, $table, $tableHistory);
  }

}
