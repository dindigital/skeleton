<?php

namespace Site\Models\DataAccess\Find\Action;

use Site\Models\DataAccess\Find\AbstractFind;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\ActionCollection;

class Action extends AbstractFind
{

  protected $_cat_link;

  public function setCatLink ( $cat_link )
  {
    $this->_cat_link = $cat_link;

  }

  /**
   *
   * @param type $id_action_cat
   * @param type $limit
   * @return \Site\Models\DataAccess\Collection\ActionCollection
   */
  public function getWidgetByIdActionCat ( $id_action_cat, $limit )
  {
    $select = new Select2('action');
    $select->addField('title');
    $select->addField('uri');
    $select->addField('date');
    $select->addField('cover');
    $select->addField('description');

    $arrCriteria = array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
        'id_action_cat = ?' => $id_action_cat,
    );

    $select->where(new Criteria($arrCriteria));

    $select->order_by('sequence=0, sequence, date DESC');
    $select->limit($limit);

    $collection = $this->_dao->select_iterator($select, new Entity\Action, new ActionCollection);

    return $collection;

  }

  /**
   *
   * @param string $uri
   * @return ActionCollection
   */
  public function getByUri ( $uri )
  {
    $select = new Select2('action');
    $select->addField('id_action');
    $select->addField('uri');
    $select->addField('short_link');
    $select->addField('title');
    $select->addField('date');
    $select->addField('cover');
    $select->addField('content');
    $select->addField('description');
    $select->addField('keywords');
    $select->addField('upd_date');

    $select->inner_join('action_cat', 'id_action_cat', 'id_action_cat');
    $select->addField('title', 'category', 'action_cat');
    $select->addField('uri', 'category_uri', 'action_cat');

    $select->where(new Criteria(array(
        'action.is_active = ?' => 1,
        'action.is_del = ?' => 0,
        'action.uri = ?' => $uri,
    )));

    $select->limit(1);

    $result = $this->_dao->select_iterator($select, new Entity\Action, new ActionCollection);

    return $result;

  }

  private function getListSelect ()
  {
    $select = new Select2('action');
    $select->addField('id_action');
    $select->addField('uri');
    $select->addField('title');
    $select->addField('date');
    $select->addField('cover');

    $select->inner_join('action_cat', 'id_action_cat', 'id_action_cat');
    $select->addField('title', 'cat_title', 'action_cat');

    $arrCriteria = array(
        'action.is_active = ?' => 1,
        'action.is_del = ?' => 0,
    );

    if ( $this->_cat_link ) {
      $arrCriteria['action_cat.uri = ?'] = $this->_cat_link;
    }

    if ( $this->_term ) {
      $arrCriteria['OR'] = array(
          'action.title LIKE ?' => $this->_term_like,
          'action.description LIKE ?' => $this->_term_like,
          'action.keywords LIKE ?' => $this->_term_like,
      );
    }

    $select->where(new Criteria($arrCriteria));

    return $select;

  }

  /**
   *
   * @return ActionCollection
   */
  public function getList ()
  {
    $select = $this->getListSelect();
    $select->order_by($this->_order);

    $select->limit($this->_limit, $this->_offset);

    $result = $this->_dao->select_iterator($select, new Entity\Action, new ActionCollection);

    return $result;

  }

  /**
   *
   * @return int
   */
  public function getListCount ()
  {
    $select = $this->getListSelect();

    return $this->_dao->select_count($select);

  }

}
