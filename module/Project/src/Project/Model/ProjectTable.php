<?php
namespace Project\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;


class ProjectTable
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

    public function fetchByUser($userID)
    {

        $resultSet = $this->tableGateway->select(array('user_id' => $userID));
        return $resultSet;
    }

    public function getProject($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveProject(Project $project)
    {
        $data = array(
            'game' => $project->game,
            'count'  => $project->count,
            'budget' => $project->budget,
            'detail_level'  => $project->detail_level,
        );


        $id = (int)$project->id;
        if ($id == 0) {
            $data['user_id'] = $project->user_id;
            $data['date_created'] = date("Y-m-d H:i:s");
            $this->tableGateway->insert($data);
        } else {
            if ($this->getProject($id)) {
                $data['date_modified'] = date("Y-m-d H:i:s");
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteProject($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}