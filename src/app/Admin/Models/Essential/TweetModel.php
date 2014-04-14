<?php

namespace Admin\Models\Essential;

use Admin\Models\Essential\BaseModelAdm;
use Twitter;
use Exception;
use Admin\Models\Essential\TweetableEntity;
use Din\Filters\Date\DateFormat;
use Admin\CustomFilter\TableFilterAdm as TableFilter;
use Din\InputValidator\InputValidator;
use Admin\Models\SocialmediaCredentialsModel;

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

    $v = new InputValidator($input);
    $v->string()->validate('msg', 'Mensagem');
    $v->throwException();

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
