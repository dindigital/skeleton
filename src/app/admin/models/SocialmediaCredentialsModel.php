<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\filters\TableFilter;

/**
 *
 * @package app.models
 */
class SocialmediaCredentialsModel extends BaseModelAdm
{

  public $row;

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('socialmedia_credentials');
  }

  public function update ( $input )
  {
    $filter = new TableFilter($this->_table, $input);
    $filter->setString('fb_app_id');
    $filter->setString('fb_app_secret');
    $filter->setString('fb_page');
    $filter->setString('fb_access_token');
    $filter->setString('tw_consumer_key');
    $filter->setString('tw_consumer_secret');
    $filter->setString('tw_access_token');
    $filter->setString('tw_access_secret');
    $filter->setString('issuu_key');
    $filter->setString('issuu_secret');
    $filter->setString('sc_client_id');
    $filter->setString('sc_client_secret');
    $filter->setString('sc_token');

    $this->dao_update(false);
  }

  public function fetchAll ()
  {
    $select = new Select('socialmedia_credentials');
    $select->addAllFields();

    $result = $this->_dao->select($select);

    $this->row = $result[0];
  }

  public function updateFbAccessToken ( $fb_access_token )
  {
    $this->_table->fb_access_token = $fb_access_token;
    $this->_dao->update($this->_table, array());
  }

  public function updateSoundCoudAccessToken ( $sc_token )
  {
    $this->_table->sc_token = $sc_token;
    $this->_dao->update($this->_table, array());
  }

}
