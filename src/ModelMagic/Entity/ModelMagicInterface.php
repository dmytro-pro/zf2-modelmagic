<?php
/**
 * Created by Dmitry Prokopenko <hellsigner@gmail.com>
 * Date: 03.06.15
 * Time: 15:30
 */

namespace ModelMagic\Entity;

interface ModelMagicInterface
{
    public function get();
    public function set($key, $value);
    public function fromArray(array $data);
    public function toArray();
}
