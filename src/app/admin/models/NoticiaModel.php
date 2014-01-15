<?php

namespace src\app\admin\models;

use src\app\admin\validators\NoticiaValidator;
use src\app\admin\models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;
use src\app\admin\helpers\Ordem;

/**
 *
 * @package app.models
 */
class NoticiaModel extends BaseModelAdm
{

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
    $select->addField('ordem');
    $select->where($arrCriteria);
    $select->order_by('a.ordem=0,a.ordem,data DESC');

    $select->inner_join('id_noticia_cat', Select::construct('noticia_cat')
                    ->addField('titulo', 'categoria'));

    $result = $this->_dao->select($select);
    $result = Ordem::setDropdown($this, $result, $arrCriteria);

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
    Ordem::setOrdem($this, $validator);
    $validator->setIncData();
    $validator->throwException();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['titulo'], $validator->getTable());

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

    $tableHistory = $this->getById($id);
    $this->_dao->update($validator->getTable(), array('id_noticia = ?' => $id));
    $this->log('U', $info['titulo'], $validator->getTable(), $tableHistory);

    return $id;
  }

}
