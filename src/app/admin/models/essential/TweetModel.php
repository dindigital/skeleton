<?php

namespace src\app\admin\models\essential;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\helpers\Entities;
use Twitter;
use Exception;
use src\app\admin\models\essential\TweetableEntity;

/**
 *
 * @package app.models
 */
class TweetModel extends BaseModelAdm
{

  protected $_model;

  public function __construct ( $section, $id )
  {
    parent::__construct();
    $this->setTable('tweet');
    $this->setModel($section, $id);
  }

  protected function setModel ( $section, $id )
  {
    $entity = Entities::getEntityByName($section);
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
    $validator = new validator($this->_table);
    $validator->setInput(array('msg' => $msg));
    $validator->setMinMaxString('msg', 'Mensagem', 1, 140);
    $validator->throwException();

    $consumer_key = TW_CONSUMER_KEY;
    $consumer_secret = TW_CONSUMER_SECRET;
    $access_token = TW_ACCESS_TOKEN;
    $access_secret = TW_ACCESS_SECRET;

    //_# ENVIA O TWEET
    try {
      $twitter = new Twitter($consumer_key, $consumer_secret, $access_token, $access_secret);
      $twitter->send($msg);
    } catch (Exception $e) {
      if ( $e->getMessage() == 'Status is a duplicate.' ) {
        throw new Exception('Essa mensagem já foi publicada anteriormente.');
      } else {
        throw new Exception('Não foi possível enviar, favor tentar novamente mais tarde.');
      }
    }

    //_# INSERE REGISTRO NA TABELA DE TWEETS
    $date = date('Y-m-d H:i:s');
    $this->_table->id_tweet = md5(uniqid());
    $this->_table->date = $date;
    $this->_table->msg = $msg;
    $this->_dao->insert($this->_table);

    //_# AVISA O MODEL
    $this->_model->sentTweet($this->_table->id_tweet);
  }

  public function getTweets ()
  {
    $tweets = $this->_model->getTweets();

    return $tweets;
  }

}
