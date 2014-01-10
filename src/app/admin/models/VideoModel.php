<?php

namespace src\app\admin\models;

use src\app\admin\validators\VideoValidator;
use src\app\admin\models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;
use \Exception;

/**
 *
 * @package app.models
 */
class VideoModel extends BaseModelAdm
{

  public function getById ( $id )
  {
    $arrCriteria = array(
        'id_video = ?' => $id
    );

    $select = new Select('video');
    $select->addField('*');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Vídeo não encontrada.');

    $row = $result[0];

    return $row;
  }

  public function listar ( $arrFilters = array(), Paginator $paginator = null )
  {
    $arrCriteria = array(
        'del = ?' => '0',
        'titulo LIKE ?' => '%' . $arrFilters['titulo'] . '%'
    );

    $select = new Select('video');
    $select->addField('id_video');
    $select->addField('ativo');
    $select->addField('titulo');
    $select->addField('data');
    $select->where($arrCriteria);
    $select->order_by('data DESC');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function inserir ( $info )
  {
    $validator = new VideoValidator();
    $id = $validator->setIdVideo()->getTable()->id_video;
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setData($info['data']);
    $validator->setDescricao($info['descricao']);
    $validator->setLinkYouTube($info['link_youtube']);
    $validator->setLinkVimeo($info['link_vimeo']);
    $validator->setIncData();
    $validator->throwException();

    $this->_dao->insert($validator->getTable());

    return $id;
  }

  public function atualizar ( $id, $info )
  {
    $validator = new VideoValidator();
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setData($info['data']);
    $validator->setDescricao($info['descricao']);
    $validator->setLinkYouTube($info['link_youtube']);
    $validator->setLinkVimeo($info['link_vimeo']);
    $validator->throwException();

    $this->_dao->update($validator->getTable(), array('id_video = ?' => $id));

    return $id;
  }

  public function excluir ( $id )
  {
    $validator = new VideoValidator();
    $validator->setDelData();
    $validator->setDel('1');
    $this->_dao->update($validator->getTable(), array('id_video = ?' => $id));
  }

  public function restaurar ( $id )
  {
    $validator = new VideoValidator();
    $validator->setDel('0');
    $this->_dao->update($validator->getTable(), array('id_video = ?' => $id));
  }

  public function excluir_permanente ( $id )
  {
    $this->_dao->delete('video', array('id_video = ?' => $id));
  }

  public function toggleAtivo ( $id, $ativo )
  {
    $validator = new VideoValidator();
    $validator->setAtivo($ativo);
    $this->_dao->update($validator->getTable(), array('id_video = ?' => $id));
  }

}
