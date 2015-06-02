<?php
/**
 * Created by Dmitry Prokopenko <hellsigner@gmail.com>
 * Date: 02.06.15
 * Time: 10:57
 */

namespace ModelMagic\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ModelMagic\EntityManager\EntityManager;

class EntityManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $em = new EntityManager;
        if ($em instanceof ServiceLocatorAwareInterface) {
            $em->setServiceLocator($serviceLocator);
        }
        $em->init();
        return $em;
    }
}
