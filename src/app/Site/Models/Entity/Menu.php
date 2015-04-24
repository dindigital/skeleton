<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;
use Site\Models\DataAccess\Collection\MenuCollection;

class Menu extends AbstractEntity
{

  public function getIdMenu ()
  {
    return $this->getField('id_menu');

  }

  public function setSubmenu ( MenuCollection $submenu )
  {
    $this->setField('submenu', $submenu);

  }

  /**
   *
   * @return MenuCollection
   */
  public function getSubmenu ()
  {
    return $this->getField('submenu');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getType ()
  {
    return $this->getField('type');

  }

  public function getUrl ()
  {
    if ( $this->getField('url') ) {
      return $this->getField('url');
    } else {
      return $this->getField('uri');
    }

  }

  public function getTarget ()
  {
    return $this->getField('target');

  }

}
