<?php
namespace Proposal\Model;

use Zend\Db\TableGateway\TableGateway;

class ProposalTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getProposal($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveProposal(Proposal $proposal)
    {
        $data = array(
            'est_cost' => $proposal->est_cost,
            'est_time'  => $proposal->est_time,
            'project_id'  => $proposal->project_id,
        );

        $id = (int)$proposal->id;
        if ($id == 0) {
            $data['user_id'] = $proposal->user_id;
            $data['date_created'] = date("Y-m-d H:i:s");
            $this->tableGateway->insert($data);
        } else {
            if ($this->getProposal($id)) {
                $data['date_modified'] = date("Y-m-d H:i:s");
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteProposal($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}