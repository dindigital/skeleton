<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Exception;
use Soundcloud\Service;
use src\app\admin\models\SocialmediaCredentialsModel;
use Din\Http\Header;
use src\app\admin\filters\TableFilter;

/**
 *
 * @package app.models
 */
class SoundCloudModel extends BaseModelAdm
{

  protected $_api;
  protected $_sm_credentials;

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('soundcloud');

    $this->_sm_credentials = new SocialmediaCredentialsModel();
    $this->_sm_credentials->fetchAll();

    $client_id = $this->_sm_credentials->row['sc_client_id'];
    $client_secret = $this->_sm_credentials->row['sc_client_secret'];
    $redirect_uri = URL . '/admin/soundcloud/auth/';

    $this->_api = new Service($client_id, $client_secret, $redirect_uri);
  }

  public function getIdName ()
  {
    return 'id_soundcloud';
  }

  public function makeLogin ()
  {
    $access_token = $this->_sm_credentials->row['sc_token'];
    if ( $access_token ) {
      $this->_api->setAccessToken($access_token);
    }

    try {
      $this->_api->get('me');
    } catch (Exception $e) {
      $authorize_url = $this->_api->getAuthorizeUrl(array(
          'scope' => 'non-expiring'
      ));

      Header::redirect($authorize_url);
    }
  }

  public function saveToken ( $input )
  {
    $token = $this->_api->accessToken($input['code']);
    $access_token = $token['access_token'];
    $this->_api->setAccessToken($access_token);

    $this->_sm_credentials->updateSoundCoudAccessToken($access_token);
  }

  public function deletePrevious ( $id_soundcloud )
  {
    try {
      $row = $this->getById($id_soundcloud);
      try {
        $this->_api->delete("tracks/{$row['track_id']}");
      } catch (Exception $e) {
        //
      }

      $this->_dao->delete('soundcloud', array(
          'id_soundcloud = ?' => $id_soundcloud
      ));
    } catch (Exception $e) {
      //
    }
  }

  public function insertComplete ( $input )
  {
    $file = '@' . $input['file'];

    $track = array(
        'track[title]' => $input['title'],
        'track[asset_data]' => $file
    );

    $response_text = $this->_api->post('tracks', $track);
    $response_json = json_decode($response_text);

    if ( json_last_error() )
      throw new Exception('Não foi possível converter pra JSON: ' . $response_text);

    $filter = new TableFilter($this->_table, array(
        'track_id' => $response_json->id,
        'track_permalink' => $response_json->permalink_url
    ));
    $filter->setNewId('id_soundcloud');
    $filter->setString('track_id');
    $filter->setString('track_permalink');

    $this->_dao->insert($this->_table);

    return $this->_table->id_soundcloud;
  }

  public function getEmbed ( $track_url )
  {
    $response_text = $this->_api->get('oembed', array(
        'url' => $track_url
    ));

    $response_json = json_decode($response_text);

    if ( json_last_error() )
      throw new Exception('Não foi possível converter pra JSON: ' . $response_text);

    return $response_json->html;
  }

}
