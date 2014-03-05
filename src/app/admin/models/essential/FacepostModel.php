<?php

namespace src\app\admin\models\essential;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Exception;
use Facebook;
use src\app\admin\models\SocialmediaCredentialsModel;
use src\app\admin\helpers\Entities;

/**
 *
 * @package app.models
 */
class FacepostModel extends BaseModelAdm
{

  protected $_model;
  protected $_facebook;
  protected $_sm_credentials;

  public function __construct ( $section, $id )
  {
    parent::__construct();
    $this->setTable('facepost');
    $this->setModel($section, $id);
    $this->_sm_credentials = new SocialmediaCredentialsModel();
    $this->_sm_credentials->fetchAll();
  }

  protected function setModel ( $section, $id )
  {
    $entity = Entities::getEntityByName($section);
    
    $this->_model = new $entity['model'];
    $this->_model->setId($id);
  }

  protected function setFacebook ()
  {
    $this->_facebook = new Facebook(array(
        'appId' => $this->_sm_credentials->row['fb_app_id'],
        'secret' => $this->_sm_credentials->row['fb_app_secret'],
    ));

    //_# IF USER IS LOGGED ON BROWSER
    if ( $this->_facebook->getUser() ) {
      // EXTEND AND STORE THE ACCESS TOKEN
      //$this->_facebook->setExtendedAccessToken();
      $access_token_request = $this->_facebook->api('/' . $this->_sm_credentials->row['fb_page'] . '?fields=access_token');

      $fb_access_token = $access_token_request['access_token'];

      $this->_sm_credentials->updateFbAccessToken($fb_access_token);
      $this->_facebook->setAccessToken($fb_access_token);
    } else {
      $this->_facebook->setAccessToken($this->_sm_credentials->row['fb_access_token']);
    }
    
  }

  public function getFacebookLogin ()
  {
    $this->setFacebook();
    
    try{
        // WILL THROW EXCEPTION IF CURRENT ACCESS TOKEN IS NOT VALID
        // THIS WAY I CAN SEND THE USER TO LOGIN URL TO GET A NEW ACCESS TOKEN
        $this->_facebook->api('/me');
    } catch (Exception $e) {
        return $this->_facebook->getLoginUrl();
    }
  }

  public function generatePost ()
  {
    $post = $this->_model->generatePost();

    return $post;
  }

  public function post ( $input )
  {
    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setRequiredString('name', 'Nome');
    $validator->setRequiredString('link', 'Link');
    $validator->throwException();

    $this->setFacebook();

    // POST TO FACEBOOK PAGE
    $params = array(
        "access_token" => $this->_sm_credentials->row['fb_access_token'],
        "message" => $input['message'],
        "link" => $input['link'],
        "picture" => $input['picture'],
        "name" => $input['name'],
//        "caption" => "Esse é o campo caption",
        "description" => $input['description'],
    );

    try {
      $this->_facebook->api('/' . $this->_sm_credentials->row['fb_page'] . '/feed', 'POST', $params);
    } catch (Exception $e) {
      Throw new Exception('Ocorreu um erro ao postar no Facebook, favor tentar novamente mais tarde.');
    }

    //_# INSERE REGISTRO NA TABELA DE TWEETS
    $date = date('Y-m-d H:i:s');
    $this->_table->id_facepost = md5(uniqid());
    $this->_table->date = $date;
    $this->_table->picture = $input['picture'];
    $this->_table->description = $input['description'];
    $this->_table->message = $input['message'];
    $this->_dao->insert($this->_table);

    //_# AVISA O MODEL
    $this->_model->sentPost($this->_table->id_facepost);
  }

  public function getPosts ()
  {
    $tweets = $this->_model->getPosts();

    return $tweets;
  }

}
