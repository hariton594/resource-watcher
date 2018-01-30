<?php
/**
 * Created by IntelliJ IDEA.
 * User: shari
 * Date: 30.01.2018
 * Time: 20:22
 */

namespace JasonLewis\ResourceWatcher\Resource;


interface ResourceCreatorInterface
{
    public function createResource($resource);
}