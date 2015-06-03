<?php
/**
 * Created by Dmitry Prokopenko <hellsigner@gmail.com>
 * Date: 02.06.15
 * Time: 12:09
 */

namespace ModelMagic\Repository;

use ModelMagic\EntityManager\EntityManagerInterface;

interface EntityRepositoryInterface
{
    public function __construct($entityClassName, EntityManagerInterface $entityManager);
    public function getAll($where = null);
    public function get($id);
    public function getOne($where);
    public function insert($data, $ignore = false);
    public function update($id, $data);
    public function updateWhere($data, $where);
    public function delete($id);
    public function deleteWhere($where);
    public function createQueryBuilder();
    public function getEntityManager();
}
