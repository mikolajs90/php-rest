<?php

namespace Rest\Repository;

use Doctrine\DBAL\Connection;
use Rest\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserRepository
{
    /* @var Connection $db */
    private $db;

    function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function getUsers(Request $request)
    {
        $where = $this->getUsersQueryConditions($request);
        $sort = $this->getUsersQuerySort($request);
        $take = $this->getUsersQueryTake($request);

        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('id', 'name', 'address', 'postal_code', 'city', 'phone', 'email', 'created_at', 'updated_at')
            ->from('user');

        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $queryBuilder->andWhere($value);
            }
        }
        $queryBuilder->orderBy($sort['by'], $sort['order']);
        $queryBuilder->setMaxResults($take);
        return $this->db->fetchAll($queryBuilder->getSQL());
    }


    private function getUsersQueryConditions(Request $request)
    {
        $where = [];
        foreach (User::$searchFields as $field => $match) {
            if ($value = htmlspecialchars($request->get($field))) {
                $tmp = ($match == 'exact' ? '' : '%');
                $where[] = "`$field` LIKE '$tmp$value$tmp' ";
            }
        }
        return $where;
    }

    private function getUsersQuerySort(Request $request)
    {
        return array(
            'by' => htmlspecialchars($request->get('sort_by', 'id')),
            'order' => htmlspecialchars($request->get('sort_order', 'ascending')) == 'descending' ? 'DESC' : 'ASC'
        );
    }

    private function getUsersQueryTake(Request $request)
    {
        return (int)$request->get('take', 10);
    }

    public function getUser($user_id)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('id', 'name', 'address', 'postal_code', 'city', 'phone', 'email', 'created_at', 'updated_at')
            ->from('user')
            ->where("id=$user_id");
        return $this->db->fetchAssoc($queryBuilder->getSQL());
    }

    public function insertUser(User $user)
    {
        if ($this->getByEmail($user->getEmail())) {
            throw new HttpException(416);
        }

        $createdAt = date('Y-m-d H:i:s');

        $sql = "INSERT INTO `user` "
            . " (`name`, `address`, `postal_code`, `city`, `phone`, `email`, `created_at`) VALUES"
            . "('" . $user->getName() . "', '" . $user->getAddress() . "', '" . $user->getPostalCode() . "', '" . $user->getCity() . "', '" . $user->getPhone() . "', '" . $user->getEmail() . "', '$createdAt')";
        try {
            $this->db->executeQuery($sql);
        } catch (\Exception $e) {
            throw new HttpException(415);
        }
    }

    public function getByEmail($email)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder
            ->select('id')
            ->from('user')
            ->where("email=$email");
        return $this->db->fetchColumn("SELECT id FROM `user` WHERE email='$email' LIMIT 1");
    }

    public function updateUser($user_id, $user)
    {
        $queryBuilder = $this->db->createQueryBuilder();
        $queryBuilder->update('user', 'u');
        foreach ($user as $key => $value) {
            $queryBuilder->set("u.$key", "'$value'");
            if ($key == 'email') {
                $id = $this->getByEmail($value);
                if ($id && $id != $user_id) {
                    throw new HttpException(416);
                }
            }
        }


        $updatedAt = date('Y-m-d H:i:s');
        $queryBuilder->set("u.updated_at", "'$updatedAt'");
        $queryBuilder->where("u.id=$user_id")->execute();
    }

    public function deleteUser($user_id)
    {
        $this->db->delete('user', array('id' => $user_id));
    }

}