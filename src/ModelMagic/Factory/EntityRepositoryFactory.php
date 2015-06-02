<?php
/**
 * Created by Dmitry Prokopenko <hellsigner@gmail.com>
 * Date: 02.06.15
 * Time: 12:55
 */

namespace ModelMagic\Factory;

use ModelMagic\EntityManager\EntityManagerInterface;
use ModelMagic\Repository\EntityRepository;
use ModelMagic\Repository\EntityRepositoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EntityRepositoryFactory
{

    /**
     * Create entity repository for given entity class.
     *
     * @param $className
     * @param EntityManagerInterface $entityManager
     * @param ServiceLocatorInterface $serviceLocator
     * @return EntityRepositoryInterface
     */
    public static function createRepository(
        $className,
        EntityManagerInterface $entityManager,
        ServiceLocatorInterface $serviceLocator
    ) {
        if (defined($className::REPOSITORY_CLASS)) {
            $repository = $className::REPOSITORY_CLASS;
            $repository = new $repository($className);
        } else {
            $repository = new EntityRepository($className, $entityManager);
        }
        if ($repository instanceof ServiceLocatorAwareInterface) {
            $repository->setServiceLocator($serviceLocator);
        }
        return $repository;
    }
}
