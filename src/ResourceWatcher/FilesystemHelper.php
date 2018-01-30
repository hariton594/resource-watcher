<?php

namespace ResourceWatcher;


class FilesystemHelper
{
    public function lastModified($path) {
        return filemtime($path);
    }

    public function exists($path) {
        return file_exists($path);
    }

    public function isDirectory($path) {
        return is_dir($path);
    }
}