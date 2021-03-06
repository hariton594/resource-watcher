<?php

namespace ResourceWatcher\Resource;

use RecursiveIteratorIterator;
use SplFileInfo;
use RecursiveDirectoryIterator;
use FilesystemIterator;
use ResourceWatcher\Event;
use ResourceWatcher\FilesystemHelper;

class DirectoryResource extends FileResource implements ResourceInterface
{
    /**
     * Array of directory resources descendants.
     *
     * @var array
     */
    protected $descendants = [];

    /**
     * Setup the directory resource.
     *
     * @return void
     */
    public function setupDirectory()
    {
        $this->descendants = $this->detectDirectoryDescendants();
    }

    /**
     * Detect any changes to the resource.
     *
     * @return array
     */
    public function detectChanges()
    {
        echo "check", $this->path, PHP_EOL;
        $events = parent::detectChanges();

        // When a descendant file is created or deleted a modified event is fired on the
        // directory. This is the only way a directory will receive a modified event and
        // will thus result in two events being fired for a single descendant modification
        // within the directory. This will clear the events if we got a modified event.
        if (! empty($events) && $events[0]->getCode() == Event::RESOURCE_MODIFIED) {
            $events = [];
        }
            foreach ($this->descendants as $key => $descendant) {
                $descendantEvents = $descendant->detectChanges();

                foreach ($descendantEvents as $event) {
                    if ($event instanceof Event && $event->getCode() == Event::RESOURCE_DELETED) {
                        unset($this->descendants[$key]);
                    }
                }

                $events = array_merge($events, $descendantEvents);
            }

        $events = $this->checkNewDescendants($events);

        return $events;
    }

    protected function checkNewDescendants($events) {
        // If this directory still exists we'll check the directories descendants again for any
        // new descendants.
        if ($this->exists) {
            foreach ($this->detectDirectoryDescendants() as $key => $descendant) {
                echo "----",$descendant->path, PHP_EOL;
                if (! isset($this->descendants[$key])) {
                    $this->descendants[$key] = $descendant;

                    $events[] = new Event($descendant, Event::RESOURCE_CREATED);
                }
            }
        }

        return $events;
    }

    /**
     * Detect the descendant resources of the directory.
     *
     * @return array
     */
    protected function detectDirectoryDescendants()
    {
        $descendants = [];

        echo "cccc", PHP_EOL;
        //foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->getPath())) as $file) {
        foreach (new RecursiveDirectoryIterator($this->getPath()) as $file) {
            //echo $file, "-------------", realpath($file), "++++++++++++", $this->getPath(), PHP_EOL;


            if ($file->isDir()
                && ! in_array($file->getBasename(), array('..'))
                && strnatcmp($this->getPath(), $file->getRealPath())!=0) {
                echo "new dir",  PHP_EOL;
                $resource = new DirectoryResource($file, $this->files);
                $descendants[$resource->getKey()] = $resource;
            } elseif ($file->isFile()) {
                $resource = new FileResource($file, $this->files);

                $descendants[$resource->getKey()] = $resource;
            }
        }

        return $descendants;
    }

    /**
     * Get the descendants of the directory.
     *
     * @return array
     */
    public function getDescendants()
    {
        return $this->descendants;
    }
}
