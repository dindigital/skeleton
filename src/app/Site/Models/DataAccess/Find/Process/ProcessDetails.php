<?php

namespace Site\Models\DataAccess\Find\Process;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\ProcessDetailsCollection;

class ProcessDetails extends AbstractDAOClient
{

    /**
     *
     * @return ProcessDetailsCollection
     */
    public function getDetails ( $id )
    {
        $select = new Select2('process_going');
        $select->addField('id_process_going');
        $select->addField('date');
        $select->addField('going');
        $select->addField('file');
        $select->addField('time');
        $select->addField('observation');

        $select->where(new Criteria(array(
            'is_del = ?' => 0,
            'id_process = ?' => $id,
        )));

        $select->order_by('date');

        $collection = $this->_dao->select_iterator($select, new Entity\ProcessDetails, new ProcessDetailsCollection);

        return $collection;

    }

    public function getProcess ( $id, $userId )
    {
        $select = new Select2('process');
        $select->addField('id_process');
        $select->addField('id_customer');
        $select->addField('order_number');
        $select->addField('process');
        $select->addField('county');
        $select->addField('stick');
        $select->addField('author');
        $select->addField('defendant');
        $select->addField('date_distribution');
        $select->addField('value');
        $select->addField('action_type');
        $select->addField('status');

        $select->where(new Criteria(array(
            'is_del = ?' => 0,
            'id_process = ?' => $id,
            'id_customer = ?' => $userId,
        )));

        $result = $this->_dao->select($select, new \Site\Models\DataAccess\Entity\Process);
        return $result[0];

    }

}
