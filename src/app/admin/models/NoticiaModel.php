<?php

namespace src\app\admin\models;

use src\app\admin\validators\NoticiaValidator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;
use src\app\admin\helpers\Ordem;
use src\app\admin\helpers\Listbox;

/**
 *
 * @package app.models
 */
class NoticiaModel extends BaseModelAdm
{

  private $_listbox;

  public function __construct ()
  {
    parent::__construct();
    $this->_listbox = new Listbox($this->_dao);
  }

  public function listar ( $arrFilters = array(), Paginator $paginator = null )
  {
    $arrCriteria = array(
        'a.del = ?' => '0',
        'a.titulo LIKE ?' => '%' . $arrFilters['titulo'] . '%'
    );
    if ( $arrFilters['id_noticia_cat'] != '' && $arrFilters['id_noticia_cat'] != '0' ) {
      $arrCriteria['a.id_noticia_cat = ?'] = $arrFilters['id_noticia_cat'];
    }

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
    $id = $validator->setId($this);
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

    $this->_listbox->insertRelationship('r_noticia_foto', 'id_noticia', $id, 'id_foto', $info['r_noticia_foto']);
    $this->_listbox->insertRelationship('r_noticia_video', 'id_noticia', $id, 'id_video', $info['r_noticia_video']);

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

    $this->_listbox->insertRelationship('r_noticia_foto', 'id_noticia', $id, 'id_foto', $info['r_noticia_foto']);
    $this->_listbox->insertRelationship('r_noticia_video', 'id_noticia', $id, 'id_video', $info['r_noticia_video']);

    return $id;
  }

  public function arrayRelationshipFoto ()
  {
    return $this->_listbox->totalArray('foto', 'id_foto', 'titulo');
  }

  public function selectedRelationshipFoto ( $id )
  {
    return $this->_listbox->selectedArray('foto', 'id_foto', 'titulo', 'r_noticia_foto', 'id_noticia', $id);
  }

  public function arrayRelationshipVideo ( $id )
  {
    return $this->_listbox->selectedArray('video', 'id_video', 'titulo', 'r_noticia_video', 'id_noticia', $id);
  }

  public function ajaxRelationshipVideo ( $term )
  {
    return $this->_listbox->ajaxJson('video', 'id_video', 'titulo', $term);
  }

}
