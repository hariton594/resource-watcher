<?php
/**
 * Created by IntelliJ IDEA.
 * User: shari
 * Date: 30.01.2018
 * Time: 20:36
 */

namespace ResourceWatcher\Resource;

use ResourceWatcher\FilesystemHelper;
use ResourceWatcher\Resource\ImportFolderResource;
use SplFileInfo;

class ImportFolderResourceCreator implements ResourceCreatorInterface
{
    public function createDirectory(SplFileInfo $resource, FilesystemHelper $files)
    {
        return new ImportFolderResource(new SplFileInfo($resource), $files);
    }

    public function createFile(SplFileInfo $resource, FilesystemHelper $files)
    {
        return new DefaultResource(new SplFileInfo($resource), $this->files);
    }
}
