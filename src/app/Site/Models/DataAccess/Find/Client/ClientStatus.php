<?php

namespace Site\Models\DataAccess\Find\Client;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\ClientStatusCollection;

class ClientStatus extends AbstractDAOClient
{

    /**
     *
     * @return ClientStatusCollection
     */
    public function getAll ( $id )
    {
        $select = new Select2('process');
        $select->addField('status');

        $select->where(new Criteria(array(
            'is_del = ?' => 0,
            'id_customer = ?' => $id
        )));

        $select->order_by('status');
        $select->group_by('status');

        $collection = $this->_dao->select_iterator($select, new Entity\ClientStatus, new ClientStatusCollection);

        return $collection;

    }

}
