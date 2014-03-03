<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\Entities;
use Exception;
use Din\DataAccessLayer\Table\Table;
use src\app\admin\models\essential\Tweetable;

/**
 *
 * @package app.models
 */
class TweetableEntity extends BaseModelAdm implements Tweetable
{

  protected $_entity;

  public function __construct ( $section, $id )
  {
    parent::__construct();

    $this->_entity = Entities::getEntityByName($section);
    $this->setTable($this->_entity['tbl']);
  }

  public function getIdName ()
  {
    return $this->_entity['id'];
  }

  public function generateTweet ()
  {
    $select = new Select($this->_entity['tbl']);
    $select->addField($this->_entity['title'], 'title');
    $select->addField('uri');
    $select->where(array(
        $this->_entity['id'] . ' = ?' => $this->getId()
    ));

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado');

    $title = $result[0]['title'];
    $url = URL . $result[0]['uri'];

    $tweet = "{$title} - Saiba mais {$url}";

    return $tweet;
  }

  public function sentTweet ( $id_tweet )
  {
    $this->_table->has_tweet = '1';
    $this->_dao->update($this->_table, array($this->_entity['id'] . ' = ?' => $this->getId()));

    //_# INSERE RELACAO
    $table = new Table('r_' . $this->_entity['tbl'] . '_tweet');
    $table->{$this->_entity['id']} = $this->getId();
    $table->id_tweet = $id_tweet;
    $this->_dao->insert($table);
  }

  public function getTweets ()
  {
    $select = new Select('tweet');
    $select->addField('date');
    $select->addField('msg');

    $select->inner_join('id_tweet', Select::construct('r_' . $this->_entity['tbl'] . '_tweet'));

    $select->where(array(
        'b.' . $this->_entity['id'] . ' = ?' => $this->getId()
    ));

    $select->order_by('date DESC');

    $result = $this->_dao->select($select);

    return $result;
  }

}
