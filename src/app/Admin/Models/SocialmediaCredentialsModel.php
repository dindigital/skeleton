<?php

namespace Admin\Models;

use Admin\Models\Essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\TableFilter\TableFilter;
use Din\Exception\JsonException;

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
    $this->setEntity('socialmedia_credentials');

  }

  public function update ( $input )
  {
    $f = new TableFilter($this->_table, $input);
    $f->string()->filter('fb_app_id');
    $f->string()->filter('fb_app_secret');
    $f->string()->filter('fb_page');
    $f->string()->filter('fb_access_token');
    $f->string()->filter('tw_consumer_key');
    $f->string()->filter('tw_consumer_secret');
    $f->string()->filter('tw_access_token');
    $f->string()->filter('tw_access_secret');
    $f->string()->filter('issuu_key');
    $f->string()->filter('issuu_secret');
    $f->string()->filter('sc_client_id');
    $f->string()->filter('sc_client_secret');
    $f->string()->filter('sc_token');
    $f->string()->filter('youtube_id');
    $f->string()->filter('youtube_secret');
    $f->string()->filter('youtube_token');

    JsonException::throwException();

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

  public function updateYouTubeAccessToken ( $youtube_token )
  {
    $this->_table->youtube_token = $youtube_token;
    $this->_dao->update($this->_table, array());

  }

}
