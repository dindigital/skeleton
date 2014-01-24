<?php

namespace src\app\admin\models;

use src\app\admin\validators\VideoValidator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;

/**
 *
 * @package app.models
 */
class VideoModel extends BaseModelAdm
{

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
    $id = $validator->setId($this);
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setData($info['data']);
    $validator->setDescricao($info['descricao']);
    $validator->setLinkYouTube($info['link_youtube']);
    $validator->setLinkVimeo($info['link_vimeo']);
    $validator->setIncData();
    $validator->throwException();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['titulo'], $validator->getTable());

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

    $tableHistory = $this->getById($id);
    $this->_dao->update($validator->getTable(), array('id_video = ?' => $id));
    $this->log('U', $info['titulo'], $validator->getTable(), $tableHistory);

    return $id;
  }

  public function getListSelectedArray ( $id )
  {
    $arrCriteria = array(
        'b.id_noticia = ?' => $id
    );

    $select = new Select('video');
    $select->addField('id_video');
    $select->addField('titulo');
    $select->inner_join('id_video', Select::construct('r_noticia_video')
                    ->addField('id_r_noticia_video'));
    $select->where($arrCriteria);
    $select->order_by('data DESC');

    $result = $this->_dao->select($select);

    $video = array();
    foreach ( $result as $row ) {
      $video[$row['id_video']] = $row['titulo'];
    }

    return $video;
  }

  public function getListAjaxArray ( $term )
  {
    $arrCriteria = array(
        'titulo LIKE ?' => '%' . $term . '%'
    );

    $select = new Select('video');
    $select->addField('id_video', 'id');
    $select->addField('titulo', 'label');
    $select->where($arrCriteria);
    $select->order_by('data DESC');

    $result = $this->_dao->select($select);

    return json_encode($result);
  }

}
