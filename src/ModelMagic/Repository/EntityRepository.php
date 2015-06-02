<?php
/**
 * Created by Dmitry Prokopenko <hellsigner@gmail.com>
 * Date: 02.06.15
 * Time: 12:09
 */

namespace ModelMagic\Repository;

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

    /**
     * @var string
     */
    protected $entityClassName;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param $entityClassName
     * @param EntityManagerInterface $entityManager
     */
    public function __construct($entityClassName, EntityManagerInterface $entityManager)
    {
        if (!class_exists($entityClassName)) {
            throw new InvalidArgumentException('Invalid classname provided.');
        }
        if (!defined($entityClassName::TABLE)) {
            throw new InvalidArgumentException('TABLE constant for the entity class must be defined');
        }
        $this->entityClassName = $entityClassName;
        $this->table = $entityClassName::TABLE;
        $this->entityManager = $entityManager;
    }

    public function getAll($where = null)
    {
        // TODO: Implement getAll() method.
    }

    public function get($id)
    {
        // TODO: Implement get() method.
    }

    public function insert($data, $ignore = false)
    {
        // TODO: Implement insert() method.
    }

    public function replace($data)
    {
        // TODO: Implement replace() method.
    }

    public function update($id, $data)
    {
        // TODO: Implement update() method.
    }

    public function createQueryBuilder()
    {
        // TODO: Implement createQueryBuilder() method.
    }

    public function getEntityManager()
    {
        return $this->entityManager;
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
