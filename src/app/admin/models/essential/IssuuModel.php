<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Table\Table;
use Din\DataAccessLayer\Select;
use Din\API\Issuu\Issuu;

/**
 *
 * @package app.models
 */
class IssuuModel extends BaseModelAdm
{

  protected $_issuu;

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('issuu');
    $this->_issuu = new Issuu(ISSUU_API_KEY, ISSUU_API_SECRET);
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
    // issuu api
    if ( $input['previous_id'] ) {
      $row = $this->selectRow($input['previous_id']);
      $this->_issuu->document_delete($row['name']);
    }

    $response = $this->_issuu->document_url_upload($input['url'], $input['name'], $input['title']);

    // salva no banco
    $this->insert(array(
        'name' => $response['name'],
        'link' => $response['link'],
        'document_id' => $response['document_id'],
        'parent_table' => 'publication',
        'parent_id_field' => 'id_publication',
        'parent_id_value' => $input['id'],
    ));
  }

  public function selectRow ( $id )
  {
    $select = new Select('issuu');
    $select->addAllFields();
    $select->where(array(
        'id_issuu' => $id
    ));

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado');

    return $result[0];
  }

  public function deleteComplete ()
  {
    $row = $this->selectRow($this->getId());

    // delete from api
    $this->_issuu->document_delete($row['name']);

    // delete from databse
    $this->_dao->delete('issuu', array(
        'id_issuu' => $this->getId()
    ));
  }

}
