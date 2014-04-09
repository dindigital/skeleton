<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Exception;
use src\app\admin\models\SocialmediaCredentialsModel;
use Google_Client;
use Google_Service_YouTube_VideoSnippet;
use Google_Service_YouTube_Video;
use Google_Service_YouTube_VideoStatus;
use Google_Service_YouTube;
use Google_Service_Exception;
use Din\Http\Header;
use Din\Session\Session;

/**
 *
 * @package app.models
 */
class YouTubeModel extends BaseModelAdm
{

  protected $_youtube;
  protected $_youtube_client;
  protected $_sm_credentials;

  public function __construct ()
  {
    parent::__construct();
    $this->_sm_credentials = new SocialmediaCredentialsModel();
    $this->_sm_credentials->fetchAll();

    $this->_youtube_client = new Google_Client();
    $this->_youtube_client->setClientId($this->_sm_credentials->row['youtube_id']);
    $this->_youtube_client->setClientSecret($this->_sm_credentials->row['youtube_secret']);
    $this->_youtube_client->setRedirectUri(URL . '/admin/youtube/');
    $this->_youtube_client->addScope(array("https://www.googleapis.com/auth/youtube"));
    $this->_youtube_client->setAccessType('offline');
    $this->_youtube_client->setApprovalPrompt('force');
    $this->_youtube = new Google_Service_YouTube($this->_youtube_client);
  }

  public function setToken ()
  {
    if ( !is_null($this->_sm_credentials->row['youtube_token']) && $this->_sm_credentials->row['youtube_token'] ) {
      $this->_youtube_client->setAccessToken($this->_sm_credentials->row['youtube_token']);
    }
  }

  protected function refreshToken ()
  {
    if ( !is_null($this->_sm_credentials->row['youtube_token']) && $this->_sm_credentials->row['youtube_token'] ) {
      $json = json_decode($this->_sm_credentials->row['youtube_token']);
      if ( isset($json->refresh_token) ) {
        $this->_youtube_client->refreshToken($json->refresh_token);
        $token = $this->_youtube_client->getAccessToken();
        $this->_sm_credentials->updateYouTubeAccessToken($token);
      }
    }
  }

  public function getYouTubeLogin ()
  {
    $this->setToken();
    if ( $this->_youtube_client->isAccessTokenExpired() ) {
      $this->refreshToken();
    }
    if ( $this->_youtube_client->isAccessTokenExpired() ) {
      $this->getLoginUrl();
    }
  }

  public function auth ( $code )
  {
    $this->_youtube_client->authenticate($code);
    $token = $this->_youtube_client->getAccessToken();

    $this->_sm_credentials->updateYouTubeAccessToken($token);
  }

  protected function getLoginUrl ()
  {
    $session = new Session('adm_session');
    $session->set('referer', Header::getUri());

    Header::redirect($this->_youtube_client->createAuthUrl());
  }

  public function insert ( $input, $privacy = "public" )
  {

    $this->setToken();

    $snippet = new Google_Service_YouTube_VideoSnippet();
    $snippet->setTitle($input['title']);
    $snippet->setDescription($input['description']);
    if ( isset($input['tags']) && is_array($input['tags']) ) {
      $snippet->setTags($input['tags']);
    }

    $status = new Google_Service_YouTube_VideoStatus();
    $status->privacyStatus = $privacy;

    $video = new Google_Service_YouTube_Video();
    $video->setSnippet($snippet);
    $video->setStatus($status);

    $file = $_SERVER['DOCUMENT_ROOT'] . '/public' . $input['file'];

    if ( !is_file($file) ) {
      throw new Exception('Problema com o caminho do arquivo');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file);

    try {
      $obj = $this->_youtube->videos->insert(
              "status,snippet", $video, array(
          "data" => file_get_contents($file),
          "mimeType" => $mime_type,
          'uploadType' => 'multipart'
              )
      );

      return $obj->id;
    } catch (Google_Service_Exception $e) {
      return false;
    }
  }

  public function delete ( $id )
  {
    try {
      $this->setToken();
      $this->_youtube->videos->delete($id);
      return true;
    } catch (Exception $e) {
      return false;
    }
  }

}
