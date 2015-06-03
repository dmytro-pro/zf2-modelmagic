<?php
/**
 * Created by Dmitry Prokopenko <hellsigner@gmail.com>
 * Date: 02.06.15
 * Time: 12:09
 */

namespace ModelMagic\Repository;

use Doctrine\DBAL\Connection;
use ModelMagic\EntityManager\EntityManagerInterface;
use ModelMagic\Exception\InvalidArgumentException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EntityRepository implements EntityRepositoryInterface, ServiceLocatorAwareInterface
{
    /**
     * @var string
     */
    protected $table;

    protected $entityClassName;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param $entityClassName
     * @param EntityManagerInterface $entityManager
     */
    public function __construct($entityClassName, EntityManagerInterface $entityManager)
    {
        if (!class_exists($entityClassName)) {
            throw new InvalidArgumentException('Invalid classname provided.');
        }
        if (!defined("$entityClassName::TABLE")) {
            throw new InvalidArgumentException('TABLE constant for the entity class must be defined');
        }
        $this->entityClassName = $entityClassName;
        $this->table = $entityClassName::TABLE;
        $this->entityManager = $entityManager;
    }


    /**
     * Retrieve a set of records.
     *
     * @param null $where
     * @param array $params
     * @param array $types
     * @return array
     */
    public function getAll($where = null, array $params = array(), array $types = array())
    {
        $qb = $this->createQueryBuilder()->select('*')->from($this->table);
        if ($where) {
            $qb->where($where);
            (!empty($params)) && ($qb->setParameters($params, $types));
        }
        return $this->mapResultSet($qb->execute()->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Retrieve a single record by primary key.
     *
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        $pk = 'id';
        $className = $this->entityClassName;
        if (defined("$className::PRIMARY_COLUMN")) {
            $pk = $className::PRIMARY_COLUMN;
        }
        $qb = $this->createQueryBuilder()
            ->select('*')
            ->from($this->table)
            ->where($pk . ' = ?')->setParameters(array($id));
        return $this->mapResult($qb->execute()->fetch(\PDO::FETCH_ASSOC));
    }

    /**
     * Implements INSERT and INSERT IGNORE operations.
     * The data must be an associative array of 'key' => 'value' items;
     * The key items must correlate with corresponding table field names.
     *
     * @param $data
     * @param bool $ignore
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function insert($data, $ignore = false)
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $markers = array();
        foreach ($values as $_) {
            $markers[] = '?';
        }
        $sql = 'INSERT';
        if ($ignore) {
            $sql .= ' IGNORE';
        }
        $sql .=' INTO '
            .$this->table
            .'(' . join($keys, ',') . ')'
            .'VALUES '
            .'(' . join($markers, ',') . ')';
        $stat = $this->getConnection()->prepare($sql);
        return $stat->execute($values);
    }

    /**
     * Implements REPLACE operation. The usage is similar to $this->insert() method.
     * https://dev.mysql.com/doc/refman/5.0/en/replace.html
     *
     * @see $this->insert()
     * @param $data
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function replace($data)
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $markers = array();
        foreach ($values as $_) {
            $markers[] = '?';
        }
        $sql = 'REPLACE'
            .=' INTO '
            .$this->table
            .'(' . join($keys, ',') . ')'
            .'VALUES '
            .'(' . join($markers, ',') . ')';
        $stat = $this->getConnection()->prepare($sql);
        return $stat->execute($values);
    }

    public function update($id, $data)
    {
        // TODO: Implement update() method.
    }

    /**
     * Map resultSet as an array of entities.
     *
     * @param $resultSet
     * @return array
     */
    public function mapResultSet($resultSet)
    {
        $mapped = array();
        $entityPrototype = $this->getEntityManager()->newEntity($this->entityClassName);
        foreach ($resultSet as $result) {
            $item = clone $entityPrototype;
            $item->setData($result);
            $mapped[] = $item;
        }
        return $mapped;
    }

    /**
     * Map single entity.
     *
     * @param $result
     * @return mixed
     */
    public function mapResult($result)
    {
        $entity = $this->getEntityManager()->newEntity($this->entityClassName);
        $entity->setData($result);
        return $entity;
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function createQueryBuilder()
    {
        return $this->getConnection()->createQueryBuilder();
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->entityManager->getConnection();
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return $this
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
