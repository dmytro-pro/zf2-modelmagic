<?php
/**
 * Created by Dmitry Prokopenko <hellsigner@gmail.com>
 * Date: 02.06.15
 * Time: 11:02
 */

namespace ModelMagic\EntityManager;

interface EntityManagerAwareInterface
{
    public function getEntityManager();
    public function setEntityManager(EntityManagerInterface $entityManager);
}
