<?php
/**
 * Created by Dmitry Prokopenko <hellsigner@gmail.com>
 * Date: 02.06.15
 * Time: 13:13
 */

namespace ModelMagic\Factory;

use ModelMagic\EntityManager\EntityManagerAwareInterface;
use ModelMagic\EntityManager\EntityManagerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EntityFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $className
     * @param array|null $data
     * @return object
     */
    public static function createEntity(ServiceLocatorInterface $serviceLocator, $className, $data = null)
    {
        if (!empty($data)) {
            $entity = new $className($data);
        } else {
            $entity = new $className();
        }
        if ($entity instanceof EntityManagerAwareInterface) {
            /** @var EntityManagerInterface $em */
            $em = $serviceLocator->get('ModelMagic/EntityManager');  // todo: decouple
            $entity->setEntityManager($em);
        }
        if ($entity instanceof ServiceLocatorAwareInterface) {
            $entity->setServiceLocator($serviceLocator);
        }
        return $entity;
    }
}
