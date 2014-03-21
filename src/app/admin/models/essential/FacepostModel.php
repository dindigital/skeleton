<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Exception;
use Facebook;
use src\app\admin\models\SocialmediaCredentialsModel;
use src\app\admin\helpers\Entities;
use Din\Filters\Date\DateFormat;
use src\app\admin\validators\StringValidator;
use src\app\admin\helpers\TableFilter;
use Din\Exception\JsonException;

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

    try {
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
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('name', "Nome");
    $str_validator->validateRequiredString('link', "Link");
    //
    JsonException::throwException();
    //
    $this->setFacebook();
    //
    // POST TO FACEBOOK PAGE
    $params = array(
        "access_token" => $this->_sm_credentials->row['fb_access_token'],
        "message" => $input['message'],
        "link" => $input['link'],
        "picture" => $input['picture'],
        "name" => $input['name'],
        //"caption" => "Esse é o campo caption",
        "description" => $input['description'],
    );

    try {
      $this->_facebook->api('/' . $this->_sm_credentials->row['fb_page'] . '/feed', 'POST', $params);
    } catch (Exception $e) {
      Throw new Exception('Ocorreu um erro ao postar no Facebook, favor tentar novamente mais tarde.');
    }
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setNewId('id_facebook');
    $filter->setTimestamp('date');
    $filter->setString('picture');
    $filter->setString('description');
    $filter->setString('message');
    //
    $this->_dao->insert($this->_table);
    //_# AVISA O MODEL
    $this->_model->sentPost($this->_table->id_facepost);

  }

  public function getPosts ()
  {
    $tweets = $this->_model->getPosts();

    foreach ( $tweets as $i => $row ) {
      $tweets[$i]['date'] = DateFormat::filter_dateTimeExtensive($row['date']);
    }

    return $tweets;
  }

}
