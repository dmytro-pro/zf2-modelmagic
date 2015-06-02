<?php
/**
 * Created by Dmitry Prokopenko <hellsigner@gmail.com>
 * Date: 02.06.15
 * Time: 11:02
 */

namespace ModelMagic\EntityManager;

interface EntityManagerInterface
{
    public function getRepository($className);
    public function newEntity($className);
    public function getConnection();
}
