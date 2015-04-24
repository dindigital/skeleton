<?php

namespace Site\Models\DataAccess\Save;

use Site\Models\DataAccess\Entity\DealerRefCommercial;

class DealerRefCommercialSave extends AbstractSave
{

    public function __construct(DealerRefCommercial $entity, $id_dealer, $sequence)
    {
        parent::__construct('dealer_ref');
        $this->setEntity($entity);
        $this->_table->id_dealer_ref = $this->getNewId();
        $this->_table->id_dealer = $id_dealer;
        $this->_table->sequence = $sequence;
        $this->_table->title = $this->_entity->getTitle();
        $this->_table->phone = $this->_entity->getPhone();
        $this->_table->contact = $this->_entity->getContact();
    }

}
