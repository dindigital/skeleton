<?php

namespace src\app\admin\models;

use src\app\admin\validators\NewsCatValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;
use src\app\admin\helpers\Sequence;

/**
 *
 * @package app.models
 */
class NewsCatModel extends BaseModelAdm
{

  public function get_list ( $arrFilters = array(), Paginator $paginator = null )
  {
    $arrCriteria = array(
        'del = ?' => '0',
        'title LIKE ?' => '%' . $arrFilters['title'] . '%'
    );
    if ( isset($arrFilters['is_home']) && $arrFilters['is_home'] == '1' ) {
      $arrCriteria['is_home = ?'] = '1';
    } elseif ( isset($arrFilters['is_home']) && $arrFilters['is_home'] == '2' ) {
      $arrCriteria['is_home = ?'] = '0';
    }

    $select = new Select('news_cat');
    $select->addField('id_news_cat');
    $select->addField('active');
    $select->addField('title');
    $select->addField('inc_date');
    $select->addField('sequence');
    $select->where($arrCriteria);
    $select->order_by('sequence');

    $result = $this->_dao->select($select);
    $result = Sequence::setDropdown($this, $result, $arrCriteria);

    return $result;
  }

  public function save ( $info )
  {
    $validator = new validator();
    $id = $validator->setId($this);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setHome($info['is_home']);
    $validator->setIncDate();
    $validator->setFile('cover', $info['cover'], $id);

    Sequence::setSequence($this, $validator);
    $validator->throwException();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['titulo'], $validator->getTable());

    return $id;
  }

  public function update ( $id, $info )
  {
    $validator = new validator();
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setHome($info['is_home']);
    $validator->setFile('cover', $info['cover'], $id);
    $validator->throwException();

    $tableHistory = $this->getById($id);
    $this->_dao->update($validator->getTable(), array('id_news_cat = ?' => $id));
    $this->log('U', $info['title'], $validator->getTable(), $tableHistory);

    return $id;
  }

  public function getListArray ()
  {
    $select = new Select('news_cat');
    $select->addField('id_news_cat');
    $select->addField('title');
    $select->where(array(
        'del = ? ' => '0'
    ));

    $result = $this->_dao->select($select);

    $arrOptions = array();
    foreach ( $result as $row ) {
      $arrOptions[$row['id_news_cat']] = $row['title'];
    }

    return $arrOptions;
  }

}
