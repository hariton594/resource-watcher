<?php
/**
 * Created by IntelliJ IDEA.
 * User: shari
 * Date: 30.01.2018
 * Time: 20:23
 */

namespace ResourceWatcher\Resource;

use ResourceWatcher\FilesystemHelper;
use SplFileInfo;

class DefaultResourceCreator implements ResourceCreatorInterface
{
    public function createDirectory(SplFileInfo $resource, FilesystemHelper $files)
    {
        return new DirectoryResource(new SplFileInfo($resource), $files);
    }

    public function createFile(SplFileInfo $resource, FilesystemHelper $files)
    {
        return new FileResource(new SplFileInfo($resource), $this->files);
    }
}