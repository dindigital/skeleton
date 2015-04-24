<?php

namespace Site\Models\DataAccess\Save;

use Site\Models\DataAccess\Entity\Dealer;

class DealerSave extends AbstractSave
{

    public function __construct(Dealer $entity)
    {
        parent::__construct('dealer');
        $this->setEntity($entity);
        $this->_table->id_dealer = $this->getNewId();
        $this->_table->name = $this->_entity->getName();
        $this->_table->email = $this->_entity->getEmail();
        $this->_table->cnpj = $this->_entity->getCnpj();
        $this->_table->rs = $this->_entity->getRs();
        $this->_table->ie = $this->_entity->getIe();
        $this->_table->address = $this->_entity->getAddress();
        $this->_table->address_code = $this->_entity->getPostal();
        $this->_table->address_city = $this->_entity->getCity();
        $this->_table->address_state = $this->_entity->getState();
        $this->_table->address_area = $this->_entity->getNeighborhood();
        $this->_table->address_number = $this->_entity->getNumber();
        $this->_table->address_complement = $this->_entity->getComplement();
        $this->_table->phone = $this->_entity->getPhone();
        $this->_table->cel = $this->_entity->getCel();
        $this->_table->operator = $this->_entity->getOperator();
        $this->_table->contact_name = $this->_entity->getContact();
    }

    public function getIdDealer()
    {
        return $this->_table->id_dealer;
    }

}
