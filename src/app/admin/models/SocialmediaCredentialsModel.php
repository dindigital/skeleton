<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;

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
  
  public function update ( $info )
  {
    $this->_table->fb_app_id = $info['fb_app_id'];
    $this->_table->fb_app_secret = $info['fb_app_secret'];
    $this->_table->fb_page = $info['fb_page'];
    $this->_table->fb_access_token = $info['fb_access_token'];
    $this->_table->tw_consumer_key = $info['tw_consumer_key'];
    $this->_table->tw_consumer_secret = $info['tw_consumer_secret'];
    $this->_table->tw_access_token = $info['tw_access_token'];
    $this->_table->tw_access_secret = $info['tw_access_secret'];
    
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

}
