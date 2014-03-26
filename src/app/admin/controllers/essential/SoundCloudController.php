<?php

namespace src\app\admin\controllers\essential;

use src\app\admin\models\essential\SoundCloudModel as model;
use Din\Http\Get;
use Din\Http\Header;
use src\app\admin\controllers\essential\BaseControllerAdm;

use Soundcloud\Service;
use src\app\admin\models\SocialmediaCredentialsModel;
use Exception;

/**
 *
 * @package app.controllers
 */
class SoundCloudController extends BaseControllerAdm
{

  protected $_model;

  public function get_auth ()
  {
    #$this->_model = new model();
    #$code = Get::text('code');
    #$this->_model->auth($code);
    #
    #Header::redirect('/admin/index/index/');
      
    $this->_sm_credentials = new SocialmediaCredentialsModel();
    $this->_sm_credentials->fetchAll();
    
    $this->_soundcloud = new Service(
            $this->_sm_credentials->row['sc_client_id'],
            $this->_sm_credentials->row['sc_client_secret'],
            URL . '/admin/soundcloud/auth/'
     );
    
    if (!isset($_GET['code'])) {
        $param = array(
          'scope' => 'non-expiring'
        );

        header("Location: " . $this->_soundcloud->getAuthorizeUrl($param));
    } else {
        $options = array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );

        $token = $this->_soundcloud->accessToken($_GET['code'], array(), $options);
        $access_token = $token['access_token'];
        $this->_soundcloud->setAccessToken($access_token);
        
        $file = $_SERVER['DOCUMENT_ROOT'] . '/system/uploads/audio/15375ef37144082b87ff6bb4071cb69d/file/charli2.mp3';
    
        $cfile = curl_file_create($file,'audio/mpeg','arquivo');
          
        $track = array(
            'track[title]' => 'arquivo',
            'track[asset_data]' => $cfile
        );
    
        $options = array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );
    
    
        try {
            $response = $this->_soundcloud->post('tracks', $track, $options);
        } catch (Exception $e) {
            var_dump($e->getMessage());
            exit;
        }
    
        // print track link
        var_dump($response);
        exit;
        
    }
      
  }

}
