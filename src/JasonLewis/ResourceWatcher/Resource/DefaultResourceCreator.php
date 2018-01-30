<?php
/**
 * Created by IntelliJ IDEA.
 * User: shari
 * Date: 30.01.2018
 * Time: 20:23
 */

namespace JasonLewis\ResourceWatcher\Resource;

use JasonLewis\ResourceWatcher\FilesystemHelper;
use SplFileInfo;

class DefaultResourceCreator implements ResourceCreatorInterface
{

    protected $files;

    public function __construct(FilesystemHelper $files)
    {
        $this->files = $files;
    }

    public function createResource($resource)
    {
        if ($this->files->isDirectory($resource)) {
            $resource = new DirectoryResource(new SplFileInfo($resource), $this->files);
            $resource->setupDirectory();
        } else {
            $resource = new FileResource(new SplFileInfo($resource), $this->files);
        }

        return $resource;
    }
}