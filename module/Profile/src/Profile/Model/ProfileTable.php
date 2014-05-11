<?php
namespace Profile\Model;

use Zend\Db\TableGateway\TableGateway;

class ProfileTable
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

    public function getProfile($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveProfile(Profile $profile)
    {
        $data = array(
            'artist' => $profile->artist,
            'title'  => $profile->title,
        );

        $id = (int)$profile->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getProfile($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteProfile($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}