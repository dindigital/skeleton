<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\API\Issuu\Issuu;
use src\app\admin\models\SocialmediaCredentialsModel;

/**
 *
 * @package app.models
 */
class IssuuEmbedModel extends BaseModelAdm
{

  protected $_issuu;
  protected $_sm_credentials;

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('issuu_embed');
    $this->_sm_credentials = new SocialmediaCredentialsModel();
    $this->_sm_credentials->fetchAll();

    $issuu_key = $this->_sm_credentials->row['issuu_key'];
    $issu_secret = $this->_sm_credentials->row['issuu_secret'];

    if ( is_null($issuu_key) && is_null($issu_secret) ) {
      throw new Exception("É necessário o preenchimento");
    }

    $this->_issuu = new Issuu($issuu_key, $issu_secret);
  }

  protected function selectRow ( $identifier )
  {
    $select = new Select('issuu_embed');
    $select->addAllFields();
    $select->where(array(
        'identifier = ?' => $identifier
    ));

    $result = $this->_dao->select($select);

    if ( !count($result) )
      return null;

    return $result[0];
  }

  protected function insert ( $identifier, $id_issuu, $embed_id, $embed_html )
  {
    $this->_table->identifier = $identifier;
    $this->_table->id_issuu = $id_issuu;
    $this->_table->embed_id = $embed_id;
    $this->_table->embed_html = $embed_html;

    $this->_dao->insert($this->_table);
  }

  public function get ( $identifier, $id_issuu, $document_id, $width, $height )
  {
    // verifica se ja existe
    $row = $this->selectRow($identifier);
    if ( $row ) {
      return $row['embed_html'];
    }

    // gera um novo
    $document_embed = $this->_issuu->document_embed_add($document_id, '480', '400');
    $embed_html = $this->_issuu->document_embed_get_html_code((string) $document_embed['id']);

    //salva o novo
    $this->insert($identifier, $id_issuu, $document_embed['id'], $embed_html);

    return $embed_html;
  }

}
