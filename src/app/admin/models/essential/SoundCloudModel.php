<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Exception;
use Soundcloud\Service;
use src\app\admin\models\SocialmediaCredentialsModel;
use src\app\admin\helpers\Entities;
use Din\Filters\Date\DateFormat;
use src\app\admin\validators\StringValidator;
use src\app\admin\helpers\TableFilter;
use Din\Exception\JsonException;
use Services_Soundcloud_Invalid_Http_Response_Code_Exception;

/**
 *
 * @package app.models
 */
class SoundCloudModel extends BaseModelAdm
{

  protected $_soundcloud;
  protected $_sm_credentials;

  public function __construct ()
  {
    parent::__construct();
    $this->_sm_credentials = new SocialmediaCredentialsModel();
    $this->_sm_credentials->fetchAll();
    
    $this->_soundcloud = new Service(
            $this->_sm_credentials->row['sc_client_id'],
            $this->_sm_credentials->row['sc_client_secret'],
            URL . '/admin/soundcloud/auth/'
     );
            
  }

  protected function setSoundCloud ()
  {
    
    if (!is_null($this->_sm_credentials->row['sc_token'])) {
       $this->_soundcloud->setAccessToken($this->_sm_credentials->row['sc_token']);
    }

  }

  public function getSoundCloudLogin ()
  {
    $this->setSoundCloud();
        
    try {
        $this->_soundcloud->get('me');
    } catch (Exception $e) {
        $this->getLoginUrl();
    }

  }
  
  public function auth($code) {
      
    $options = array(
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    );
      
    $token = $this->_soundcloud->accessToken($code, array(), $options);
    $access_token = $token['access_token'];
    $this->_soundcloud->setAccessToken($access_token);
    
    $this->_sm_credentials->updateSoundCoudAccessToken($access_token);
  }

  protected function getLoginUrl() {
    $param = array(
      'scope' => 'non-expiring'
    );

    header("Location: " . $this->_soundcloud->getAuthorizeUrl($param));
  }
  
  public function insertComplete($input) {
      
    $this->setSoundCloud();
        
    $cfile = curl_file_create($input['file'],'audio/mpeg','test_name');
          
    $track = array(
        'track[title]' => $input['title'],
        'track[asset_data]' => $cfile
    );
    
    $options = array(
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    );
    
    $response = $this->_soundcloud->post('tracks', $track, $options);
    
    // print track link
    var_dump($response);
    exit;
      
      
  }

}
