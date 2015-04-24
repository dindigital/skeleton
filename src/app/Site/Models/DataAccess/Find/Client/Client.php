<?php

namespace Site\Models\DataAccess\Find\Client;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\ClientCollection;

class Client extends AbstractDAOClient
{

    /**
     *
     * @return ClientCollection
     */
    public function getClient ( $id )
    {
        $select = new Select2('customer');
        $select->addField('id_customer');
        $select->addField('name');
        $select->addField('business_name');
        $select->addField('document');
        $select->addField('email');
        $select->addField('address_postcode');
        $select->addField('address_street');
        $select->addField('address_area');
        $select->addField('address_number');
        $select->addField('address_complement');
        $select->addField('address_state');
        $select->addField('address_city');
        $select->addField('phone_ddd');
        $select->addField('phone_number');
        $select->addField('inc_date');
        $select->addField('website');
        $select->addField('logo');

        $select->where(new Criteria(array(
            'is_del = ?' => 0,
            'id_customer = ?' => $id
        )));

        $select->limit(1);

        $result = $this->_dao->select($select, new Entity\Client);
        //$collection = $this->_dao->select_iterator($select, new Entity\Client, new ClientCollection);

        return $result[0];

    }

}
