<?php

namespace Site\Models\Business\AbstractFactory;

abstract class AbstractFactory
{

  abstract protected function getArrayAreas ();

  abstract protected function getAreasNamespace ();

  protected $_areas;

  public function __construct ()
  {
    foreach ( $this->getArrayAreas() as $area_name ) {
      $area_class = $this->getAreasNamespace() . ucfirst($area_name);

      if ( !class_exists($area_class) )
        throw new Exception\AreaNotFoundException('Classe não encontrada: ' . $area_class);

      $this->_areas[$area_name] = new $area_class;
    }

  }

  public function getAll ()
  {
    return $this->_areas;

  }
  
}