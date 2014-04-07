<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Twitter;
use Exception;
use src\app\admin\models\essential\TweetableEntity;
use Din\Filters\Date\DateFormat;
use src\app\admin\validators\StringValidator;
use Din\Exception\JsonException;
use src\app\admin\custom_filter\TableFilterAdm as TableFilter;
use src\app\admin\models\SocialmediaCredentialsModel;

/**
 *
 * @package app.models
 */
class TweetModel extends BaseModelAdm
{

  protected $_model;
  protected $_sm_credentials;

  public function __construct ( $section, $id )
  {
    parent::__construct();
    $this->setTable('tweet');
    $this->setModel($section, $id);
    $this->_sm_credentials = new SocialmediaCredentialsModel();
    $this->_sm_credentials->fetchAll();
  }

  protected function setModel ( $section, $id )
  {
    $this->_model = new TweetableEntity($section, $id);
    $this->_model->setId($id);
  }

  public function generateTweet ()
  {
    $tweet = $this->_model->generateTweet();

    return $tweet;
  }

  public function sendTweet ( $msg )
  {
    $input = array(
        'msg' => $msg
    );

    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('msg', "Mensagem");
    //
    JsonException::throwException();

    $consumer_key = $this->_sm_credentials->row['tw_consumer_key'];
    $consumer_secret = $this->_sm_credentials->row['tw_consumer_secret'];
    $access_token = $this->_sm_credentials->row['tw_access_token'];
    $access_secret = $this->_sm_credentials->row['tw_access_secret'];

    //_# ENVIA O TWEET
    try {
      $twitter = new Twitter($consumer_key, $consumer_secret, $access_token, $access_secret);
      $twitter->send($msg);
    } catch (Exception $e) {
      if ( $e->getMessage() == 'Status is a duplicate.' ) {
        throw new Exception('Essa mensagem já foi publicada anteriormente.');
      } else {
        throw new Exception('Erro ao enviar tweet: ' . $e->getMessage());
      }
    }

    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_tweet');
    $f->timestamp()->filter('date');
    $f->string()->filter('msg');
    //
    $this->_dao->insert($this->_table);

    //_# AVISA O MODEL
    $this->_model->sentTweet($this->_table->id_tweet);
  }

  public function getTweets ()
  {
    $tweets = $this->_model->getTweets();
    foreach ( $tweets as $i => $row ) {
      $tweets[$i]['date'] = DateFormat::filter_dateTimeExtensive($row['date']);
    }
    return $tweets;
  }

}
