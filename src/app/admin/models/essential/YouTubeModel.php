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
    $this->_youtube_client->setRedirectUri(URL . '/admin/youtube/auth/');
    $this->_youtube_client->addScope(array("https://www.googleapis.com/auth/youtube"));
    $this->_youtube_client->setAccessType('offline');
    //$this->_youtube_client->setApprovalPrompt('force');
    $this->_youtube = new Google_Service_YouTube($this->_youtube_client);
            
  }
  
  public function setToken() {        
       $this->_youtube_client->setAccessToken($this->_sm_credentials->row['youtube_token']);
  }

  public function getYouTubeLogin ()
  {
    $this->setToken();
    if ($this->_youtube_client->isAccessTokenExpired()) {
        $this->getLoginUrl();
    }
  }
  
  public function auth($code) {
    $this->_youtube_client->authenticate($code);
    $token = $this->_youtube_client->getAccessToken();
        
    $this->_sm_credentials->updateYouTubeAccessToken($token);
  }

  protected function getLoginUrl() {
    header("Location: " . $this->_youtube_client->createAuthUrl());
  }
  
  public function insert($input, $privacy = "public") {
      
    $this->setToken();
        
    $snippet = new Google_Service_YouTube_VideoSnippet();
    $snippet->setTitle($input['title']);
    $snippet->setDescription($input['description']);
    if (isset($input['tags']) && is_array($input['tags'])) {
        $snippet->setTags($input['tags']);
    }
    //$snippet->setCategoryId(17);

    $status = new Google_Service_YouTube_VideoStatus();
    $status->privacyStatus = $privacy;

    $video = new Google_Service_YouTube_Video();
    $video->setSnippet($snippet);
    $video->setStatus($status);

    $file = $_SERVER['DOCUMENT_ROOT'] . '/public' . $input['file'];
        
    if (!is_file($file)) {
        throw new Exception('Problema com o caminho do arquivo');
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file);

    try {
        $obj = $this->_youtube->videos->insert(
                "status,snippet", 
                $video,
                array(
                    "data"=>file_get_contents($file), 
                    "mimeType" => $mime_type,
                    'uploadType' => 'multipart'
                )
            );

        return $obj->id;

    } catch(Google_Service_Exception $e) {
        return false;
    }
    
  }
    
  
  /*
"id": "1",
"title": "Film & Animation"
 
"id": "2",
"title": "Autos & Vehicles"
 
"id": "10",
"title": "Music"
 
"id": "15",
"title": "Pets & Animals"
 
"id": "17",
"title": "Sports"
 
"id": "18",
"title": "Short Movies"
 
"id": "19",
"title": "Travel & Events"
 
"id": "20",
"title": "Gaming"
 
"id": "21",
"title": "Videoblogging"
 
"id": "22",
"title": "People & Blogs"
 
"id": "23",
"title": "Comedy"
 
"id": "24",
"title": "Entertainment"
 
"id": "25",
"title": "News & Politics"
 
"id": "26",
"title": "Howto & Style"
 
"id": "27",
"title": "Education"
 
"id": "28",
"title": "Science & Technology"
 
"id": "29",
"title": "Nonprofits & Activism"
 
// Start Movie Tags
"id": "30",
"title": "Movies"
 
"id": "31",
"title": "Anime/Animation"
 
"id": "32",
"title": "Action/Adventure"
 
"id": "33",
"title": "Classics"
 
"id": "34",
"title": "Comedy"
 
"id": "35",
"title": "Documentary"
 
"id": "36",
"title": "Drama"
 
"id": "37",
"title": "Family"
 
"id": "38",
"title": "Foreign"
 
"id": "39",
"title": "Horror"
 
"id": "40",
"title": "Sci-Fi/Fantasy"
 
"id": "41",
"title": "Thriller"
 
"id": "42",
"title": "Shorts"
 
"id": "43",
"title": "Shows"
 
"id": "44",
"title": "Trailers"
   * 
   * 
   */
  
  public function delete($id) {
      try {
        $this->setToken();
        $this->_youtube->videos->delete($id);
        return true;
      } catch (Exception $e) {
          return false;
      }
  }

}
