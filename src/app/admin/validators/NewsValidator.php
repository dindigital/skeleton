<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Din\Exception\JsonException;
use Din\Filters\Date\DateToSql;
use src\app\admin\models\NewsCatModel;

class NewsValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('news');
  }

  public function setIdNewsCat ( $id_news_cat )
  {
    $news_cat = new NewsCatModel();
    $count = $news_cat->getCount($id_news_cat);

    if ( $count == 0 )
      return JsonException::addException('Categoria não encontrada.');

    $this->_table->id_news_cat = $id_news_cat;

    return $this;
  }

  public function setTitle ( $title )
  {
    if ( $title == '' )
      return JsonException::addException('Titulo é obrigatório');

    $this->_table->title = $title;
  }

  public function setDate ( $date )
  {
    if ( !DateToSql::validate($date) )
      return JsonException::addException('Data é obrigatório');

    $date = DateToSql::filter_date($date);

    $this->_table->date = $date;
  }

  public function setHead ( $head )
  {
    $this->_table->head = $head;
  }

  public function setBody ( $body )
  {
    $this->_table->body = $body;
  }

}
