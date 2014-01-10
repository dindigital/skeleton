<?php

namespace src\app\admin\models;

use src\app\admin\validators\NoticiaValidator;
use src\app\admin\models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;
use \Exception;
use Din\File\Folder;
use src\app\admin\models\HierarquiaModel;

/**
 *
 * @package app.models
 */
class NoticiaModel extends BaseModelAdm
{

  public function getById ( $id )
  {
    $arrCriteria = array(
        'id_noticia = ?' => $id
    );

    $select = new Select('noticia');
    $select->addField('*');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Notícia não encontrada.');

    $row = $result[0];

    return $row;
  }

  public function listar ( $arrFilters = array(), Paginator $paginator = null )
  {
    $arrCriteria = array(
        'a.del = ?' => '0',
        'a.titulo LIKE ?' => '%' . $arrFilters['titulo'] . '%'
    );

    $select = new Select('noticia');
    $select->addField('id_noticia');
    $select->addField('ativo');
    $select->addField('titulo');
    $select->addField('data');
    $select->where($arrCriteria);
    $select->order_by('data DESC');

    $select->inner_join('id_noticia_cat', Select::construct('noticia_cat')
                    ->addField('titulo', 'categoria'));

    $result = $this->_dao->select($select);

    return $result;
  }

  public function inserir ( $info )
  {
    $validator = new NoticiaValidator();
    $id = $validator->setIdNoticia()->getTable()->id_noticia;
    $validator->setIdNoticiaCat($info['id_noticia_cat']);
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setData($info['data']);
    $validator->setChamada($info['chamada']);
    $validator->setCorpo($info['corpo']);
    $validator->setArquivo('capa', $info['capa'], $id);
    $validator->setIncData();
    $validator->throwException();

    try {
      $this->_dao->insert($validator->getTable());
      $this->log('C', $info['titulo'], $validator->getTable());
    } catch (Exception $e) {
      JsonException::addException($e->getMessage());
      JsonException::throwException();
    }

    return $id;
  }

  public function atualizar ( $id, $info )
  {
    $validator = new NoticiaValidator();
    $validator->setIdNoticiaCat($info['id_noticia_cat']);
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setData($info['data']);
    $validator->setChamada($info['chamada']);
    $validator->setCorpo($info['corpo']);
    $validator->setArquivo('capa', $info['capa'], $id);
    $validator->throwException();

    try {
      $tableHistory = $this->getById($id);
      $this->_dao->update($validator->getTable(), array('id_noticia = ?' => $id));
      $this->log('U', $info['titulo'], $validator->getTable(), $tableHistory);
    } catch (Exception $e) {
      JsonException::addException($e->getMessage());
      JsonException::throwException();
    }

    return $id;
  }

}
