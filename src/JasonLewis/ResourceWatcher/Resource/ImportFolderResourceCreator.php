<?php
/**
 * Created by IntelliJ IDEA.
 * User: shari
 * Date: 30.01.2018
 * Time: 20:36
 */

namespace JasonLewis\ResourceWatcher\Resource;

use JasonLewis\ResourceWatcher\FilesystemHelper;
use JasonLewis\ResourceWatcher\Resource\ImportFolderResource;
use SplFileInfo;

class ImportFolderResourceCreator implements ResourceCreatorInterface
{
    protected $files;

    public function __construct(FilesystemHelper $files)
    {
        $this->files = $files;
    }

    public function createResource($resource)
    {
        if ($this->files->isDirectory($resource)) {
            echo "ImportFolderResourceCreator", PHP_EOL;
            $resource = new ImportFolderResource(new SplFileInfo($resource), $this->files);
            $resource->setupDirectory();
        } else {
            throw new RuntimeException('Resource must be directory.');
        }

        return $resource;
    }
}