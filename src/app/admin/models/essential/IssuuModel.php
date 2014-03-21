<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Table\Table;
use Din\DataAccessLayer\Select;
use Din\API\Issuu\Issuu;
use src\app\admin\models\SocialmediaCredentialsModel;
use Exception;

/**
 *
 * @package app.models
 */
class IssuuModel extends BaseModelAdm
{

  protected $_issuu;
  protected $_sm_credentials;

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('issuu');
    $this->_sm_credentials = new SocialmediaCredentialsModel();
    $this->_sm_credentials->fetchAll();

    $issuu_key = $this->_sm_credentials->row['issuu_key'];
    $issu_secret = $this->_sm_credentials->row['issuu_secret'];

    if ( is_null($issuu_key) && is_null($issu_secret) ) {
      throw new Exception("É necessário o preenchimento do issuu_key e issuu_secret");
    }

    $this->_issuu = new Issuu($issuu_key, $issu_secret);
  }

  public function getIdName ()
  {
    return 'id_issuu';
  }

  public function insert ( $input )
  {
    $this->setNewId();
    $this->_table->name = $input['name'];
    $this->_table->link = $input['link'];
    $this->_table->document_id = $input['document_id'];

    $this->_dao->insert($this->_table);

    $parent_table = new Table($input['parent_table']);
    $parent_table->id_issuu = $this->getId();

    $this->_dao->update($parent_table, array(
        "{$input['parent_id_field']} = ?" => $input['parent_id_value']
    ));
  }

  public function insertComplete ( $input )
  {
    // deleta o anterior (substituir)
    if ( $input['previous_id'] ) {
      $parent_table = new Table($input['parent_table']);
      $parent_table->id_issuu = null;

      $this->_dao->update($parent_table, array(
          "{$input['parent_id_field']} = ?" => $input['parent_id_value']
      ));

      $this->deleteComplete($input['previous_id']);
    }

    // insere na api
    $response = $this->_issuu->document_url_upload($input['url'], $input['name'], $input['title']);

    // salva no banco
    $this->insert(array(
        'name' => $response['name'],
        'link' => $response['link'],
        'document_id' => $response['document_id'],
        'parent_table' => $input['parent_table'],
        'parent_id_field' => $input['parent_id_field'],
        'parent_id_value' => $input['parent_id_value'],
    ));
  }

  public function selectRow ( $id )
  {
    $select = new Select('issuu');
    $select->addAllFields();
    $select->where(array(
        'id_issuu = ?' => $id
    ));

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado');

    return $result[0];
  }

  public function deleteComplete ( $id = null )
  {
    $id = !is_null($id) ? $id : $this->getId();
    $row = $this->selectRow($id);

    // delete from api
    $this->_issuu->document_delete($row['name']);

    // delete from databse
    $this->_dao->delete('issuu', array(
        'id_issuu = ?' => $id
    ));
  }

}
