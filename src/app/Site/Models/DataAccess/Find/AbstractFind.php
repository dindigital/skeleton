<?php

namespace Site\Models\DataAccess\Find;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Find\Order;

class AbstractFind extends AbstractDAOClient
{

  protected $_limit;
  protected $_offset;
  protected $_skip_ids = array();
  protected $_order;
  protected $_term;
  protected $_term_like;

  public function setTerm ( $term )
  {
    $this->_term = $term;
    $this->_term_like = '%' . $this->_term . '%';

  }

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

  /**
   *
   * @param array $ids
   */
  public function setSkipId ( array $ids )
  {
    $this->_skip_ids = $ids;

  }

  public function setOrder ( Order $order )
  {
    $this->_order = $order;

  }

}
