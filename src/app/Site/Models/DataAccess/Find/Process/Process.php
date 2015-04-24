<?php

namespace Site\Models\DataAccess\Find\Process;

use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Find\AbstractFind;
use Site\Models\DataAccess\Collection\ProcessCollection;

class Process extends AbstractFind
{

    protected $_offset;

    public function __construct ()
    {
        parent::__construct();
        $this->selectAll();

    }

    public function selectAll ()
    {
        $this->_select = new Select2('process');
        $this->_select->addField('id_process');
        $this->_select->addField('process');
        $this->_select->addField('order_number');
        $this->_select->addField('county');
        $this->_select->addField('stick');
        $this->_select->addField('author');
        $this->_select->addField('defendant');
        $this->_select->addField('date_distribution');
        $this->_select->addField('value');
        $this->_select->addField('action_type');
        $this->_select->addField('status');

        $this->_criteria = array(
            'is_del = ?' => 0
        );

    }

    public function getTotal ( $id_client, $input )
    {
        $this->_criteria['id_customer = ?'] = $id_client;

        if ( count($input) > 0 ) {
            $this->setSearchWhere($input);
        }
        $this->_select->where(new Criteria($this->_criteria));
        return $this->_dao->select_count($this->_select);

    }

    public function prepare ()
    {
        $this->_select->where(new Criteria($this->_criteria));

    }

    //adiciona os campos buscados no where
    public function setSearchWhere ( $input )
    {
        foreach ( $input as $key => $value ) {

            if ( $key === 'processnumber' && trim($value) !== "" ) {
                $this->_criteria['process = ?'] = $value;
            }

            if ( $key === 'processstatus' && trim($value) !== "" ) {
                $this->_criteria['status = ?'] = $value;
            }

            if ( $key === 'text' && trim($value) !== "" ) {

                $this->_criteria['OR']['county LIKE ?'] = '%' . $value . '%';
                $this->_criteria['OR']['stick LIKE ?'] = '%' . $value . '%';
                $this->_criteria['OR']['author LIKE ?'] = '%' . $value . '%';
                $this->_criteria['OR']['defendant LIKE ?'] = '%' . $value . '%';
                $this->_criteria['OR']['value LIKE ?'] = '%' . $value . '%';
            }
        }

    }

    public function setLimit ( $limit )
    {
        $this->_select->limit($limit, $this->_offset);

    }

    public function setOffset ( $offset )
    {
        $this->_offset = $offset;

    }

    public function getAll ()
    {
        $collection = $this->_dao->select_iterator($this->_select, new \Site\Models\DataAccess\Entity\Process, new ProcessCollection);
        return $collection;

    }

}
