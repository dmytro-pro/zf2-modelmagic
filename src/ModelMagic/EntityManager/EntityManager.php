<?php
/**
 * Created by Dmitry Prokopenko <hellsigner@gmail.com>
 * Date: 02.06.15
 * Time: 10:56
 */

namespace ModelMagic\EntityManager;

use Doctrine\DBAL\DriverManager;
use ModelMagic\Factory\EntityFactory;
use ModelMagic\Factory\EntityRepositoryFactory;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\DBAL\Connection;

class EntityManager implements ServiceLocatorAwareInterface, EntityManagerInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var Connection
     */
    protected $connection;

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

    /**
     * @param $className
     * @return \ModelMagic\Repository\EntityRepositoryInterface
     */
    public function getRepository($className)
    {
        return EntityRepositoryFactory::createRepository($className, $this, $this->serviceLocator);
    }

    /**
     * @param $className
     * @param array $data
     * @return object
     */
    public function newEntity($className, $data = array())
    {
        return EntityFactory::createEntity($this->serviceLocator, $className, $data);
    }

    /**
     * Create new DBAL connection
     *
     * @param null $dbConfig
     * @return Connection
     * @throws \Doctrine\DBAL\DBALException
     */
    public function createConnection($dbConfig = null)
    {
        if (!$dbConfig) {
            $dbConfig = $this->getServiceLocator()->get('config');
            $dbConfig = $dbConfig['db'];
            // "Adapter" for Zend DB
            if (empty($dbConfig['dbname']) && !empty($dbConfig['database'])) {
                $dbConfig['dbname'] = $dbConfig['database'];
            }
            if (empty($dbConfig['user']) && !empty($dbConfig['username'])) {
                $dbConfig['user'] = $dbConfig['username'];
            }
            (!empty($dbConfig['driver'])) && ($dbConfig['driver'] = strtolower($dbConfig['driver']));
        }
        return DriverManager::getConnection($dbConfig);
    }

    /**
     * Set DBAL connection
     *
     * @param \Doctrine\DBAL\Driver\Connection $connection
     * @return $this
     */
    public function setConnection(\Doctrine\DBAL\Driver\Connection $connection)
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * Get DBAL connection
     *
     * @return Connection
     */
    public function getConnection()
    {
        if (!$this->connection) {
            $this->connection = $this->createConnection();
        }
        return $this->connection;
    }

    /**
     * Invokes after DI
     */
    public function init()
    {
        $this->setConnection($this->createConnection());
    }
}
