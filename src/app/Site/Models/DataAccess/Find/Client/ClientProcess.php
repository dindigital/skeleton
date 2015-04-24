<?php

namespace Site\Models\DataAccess\Find\Client;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\ClientProcessCollection;

class ClientProcess extends AbstractDAOClient
{

    /**
     *
     * @return ClientProcessCollection
     */
    public function getAll ( $id )
    {
        $select = new Select2('process');
        $select->addField('process');
        $select->addField('order_number');

        $select->where(new Criteria(array(
            'is_del = ?' => 0,
            'id_customer = ?' => $id
        )));

        $select->order_by('process');
        $select->group_by('process');

        $collection = $this->_dao->select_iterator($select, new Entity\ClientProcess, new ClientProcessCollection);

        return $collection;

    }

}
