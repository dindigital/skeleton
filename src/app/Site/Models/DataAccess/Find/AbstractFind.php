<?php

namespace Site\Models\DataAccess\Find;

use Site\Models\DataAccess\Connection\AbstractDAOClient;

class AbstractFind extends AbstractDAOClient
{

  protected $_select;
  protected $_criteria;
  protected $_limit;
  protected $_offset;

  /**
   *
   * @param int $limit
   */
  public function setLimit ( $limit )
  {
    $this->_limit = $limit;

  }

  /**
   *
   * @param int $offset
   */
  public function setOffset ( $offset )
  {
    $this->_offset = $offset;

  }

  public function getCount ()
  {
    return $this->_dao->select_count($this->_select);

  }

}
