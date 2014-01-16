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

    $select->inner_join('id_pagina_Cat', Select::construct('pagina_cat')
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

}
