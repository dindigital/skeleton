<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Table\Table;
use Din\API\Issuu\Issuu;
use src\app\admin\models\SocialmediaCredentialsModel;
use Exception;
use src\app\admin\custom_filter\TableFilterAdm as TableFilter;

/**
 *
 * @package app.models
 */
class IssuuModel extends BaseModelAdm
{

  protected $_api;

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('issuu');

    $this->setApi();

  }

  protected function setApi ()
  {
    $sm_credentials = new SocialmediaCredentialsModel();
    $sm_credentials->fetchAll();

    $issuu_key = $sm_credentials->row['issuu_key'];
    $issu_secret = $sm_credentials->row['issuu_secret'];

    if ( is_null($issuu_key) && is_null($issu_secret) ) {
      throw new Exception("É necessário o preenchimento do issuu_key e issuu_secret");
    }

    $this->_api = new Issuu($issuu_key, $issu_secret);

  }

  public function getIdName ()
  {
    return 'id_issuu';

  }

  /**
   *
   * @param type $input array(
   * 'url' => '',
   * ' name' => '',
   * 'title' => '',
   * 'parent_table' => '',
   * 'parent_id_field' => '',
   * 'parent_id_value' => '',
   * 'id_issuu' => '',
   * 'delete_previous' => ''
   * )
   */
  public function insertComplete ( $input )
  {
    if ( $input['delete_previous'] ) {
      $this->deleteComplete($input);
    }

    // insere na api
    $response = $this->_api->document_url_upload($input['url'], $input['name'], $input['title']);

    // insere na tabela issuu
    $f = new TableFilter($this->_table, array(
        'document_id' => $response['document_id'],
        'name' => $response['name'],
        'link' => $response['link'],
    ));

    $f->newId()->filter('id_issuu');
    $f->string()->filter('name');
    $f->string()->filter('link');
    $f->string()->filter('document_id');

    $this->_dao->insert($this->_table);

    // atualiza tabela parent com o id da tabela issuu
    $parent_table = new Table($input['parent_table']);
    $parent_table->id_issuu = $this->getId();

    $this->_dao->update($parent_table, array(
        "{$input['parent_id_field']} = ?" => $input['parent_id_value']
    ));

  }

  /**
   *
   * @param type $input = array(
   * 'id_issuu'=>'',
   * 'parent_table'=>'',
   * 'parent_id_field'=>'',
   * 'parent_id_value'=>'',
   * )
   */
  public function deleteComplete ( $input )
  {
    // seta nulo na tabela holder
    $parent_table = new Table($input['parent_table']);
    $parent_table->id_issuu = null;

    $this->_dao->update($parent_table, array(
        "{$input['parent_id_field']} = ?" => $input['parent_id_value']
    ));

    // pega o document_name para fazer a deleção da api
    $row = $this->getById($input['id_issuu']);
    $document_name = $row['name'];

    // deleta da api
    $this->_api->document_delete($document_name);

    // deleta na tabela issuu
    $this->_dao->delete('issuu', array(
        'id_issuu = ?' => $input['id_issuu']
    ));

  }

}
