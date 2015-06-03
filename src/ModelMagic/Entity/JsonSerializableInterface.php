<?php
/**
 * Created by Dmitry Prokopenko <hellsigner@gmail.com>
 * Date: 03.06.15
 * Time: 15:44
 */

namespace ModelMagic\Entity;

interface JsonSerializableInterface
{
    public function fromJSON($json);
    public function toJSON();
}
