<?php
/**
 * Created by IntelliJ IDEA.
 * User: shari
 * Date: 30.01.2018
 * Time: 20:22
 */

namespace JasonLewis\ResourceWatcher\Resource;

use JasonLewis\ResourceWatcher\FilesystemHelper;
use SplFileInfo;

interface ResourceCreatorInterface
{
    public function createDirectory(SplFileInfo $resource, FilesystemHelper $files);
    public function createFile(SplFileInfo $resource, FilesystemHelper $files);
}