<?php

namespace Site\Models\DataAccess\Find;

class Order
{

  protected $_orders;

  /**
   *
   * @param array $orders
   */
  public function __construct ( array $orders = array() )
  {
    $this->_orders = $orders;

  }

  /**
   *
   * @param string $order
   */
  public function add ( $order )
  {
    $this->_orders[] = $order;

  }

  public function getOrder ()
  {
    return implode(',', $this->_orders);

  }

  public function __toString ()
  {
    return $this->getOrder();

  }

}
