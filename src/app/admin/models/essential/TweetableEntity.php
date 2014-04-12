<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Exception;
use Din\DataAccessLayer\Table\Table;
use src\app\admin\models\essential\Tweetable;
use Din\UrlShortener\Bitly\Bitly;
use Din\Filters\String\LimitChars;

/**
 *
 * @package app.models
 */
class TweetableEntity extends BaseModelAdm implements Tweetable
{

  public function __construct ( $section, $id )
  {
    parent::__construct();

    $this->_entity = $this->_entities->getEntity($section);
    $entity_tbl = $this->_entity->getTbl();

    $this->setTable($entity_tbl);

  }

  public function getIdName ()
  {
    return $this->_entity->getId();

  }

  public function generateTweet ()
  {
    $entity_tbl = $this->_entity->getTbl();
    $entity_title = $this->_entity->getTitle();
    $entity_id = $this->_entity->getId();

    $select = new Select($entity_tbl);
    $select->addField($entity_title, 'title');
    $select->addField('uri');
    $select->where(array(
        "{$entity_id} = ?" => $this->getId()
    ));

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado');

    $title = $result[0]['title'];
    $title = LimitChars::filter($title, 100, '...');
    $url = URL . $result[0]['uri'];

    //shorten url
    try {
      $bitly = new Bitly(BITLY);
      $bitly->shorten($url);
      $short_url = $bitly->getShortUrl();
    } catch (Exception $e) {
      //mute exception, just continue with big url
      $short_url = $url;
    }

    $tweet = "{$title} - Saiba mais {$short_url}";

    return $tweet;

  }

  public function sentTweet ( $id_tweet )
  {
    $entity_id = $this->_entity->getId();
    $entity_tbl = $this->_entity->getTbl();

    $this->_table->has_tweet = '1';
    $this->_dao->update($this->_table, array("{$entity_id} = ?" => $this->getId()));

    //_# INSERE RELACAO
    $table = new Table("r_{$entity_tbl}_tweet");
    $table->{$entity_id} = $this->getId();
    $table->id_tweet = $id_tweet;
    $this->_dao->insert($table);

  }

  public function getTweets ()
  {
    $entity_id = $this->_entity->getId();
    $entity_tbl = $this->_entity->getTbl();

    $select = new Select('tweet');
    $select->addField('date');
    $select->addField('msg');

    $select->inner_join('id_tweet', Select::construct("r_{$entity_tbl}_tweet"));

    $select->where(array(
        "b.{$entity_id} = ?" => $this->getId()
    ));

    $select->order_by('date DESC');

    $result = $this->_dao->select($select);

    return $result;

  }

}
