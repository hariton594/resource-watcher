<?php
/**
 * Created by IntelliJ IDEA.
 * User: shari
 * Date: 30.01.2018
 * Time: 20:04
 */

namespace JasonLewis\ResourceWatcher\Resource;

use RecursiveDirectoryIterator;

class ImportFolderResource extends DirectoryResource
{
    /**
     * Detect any changes to the resource.
     *
     * @return array
     */
    public function detectChanges() {

        $events = [];


        foreach ($this->descendants as $key => $descendant) {
            $descendantEvents = $descendant->detectExist();

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


    protected function detectDirectoryDescendants()
    {
        $descendants = [];

        foreach (new RecursiveDirectoryIterator($this->getPath()) as $file) {
            if ($file->isDir()
                && ! in_array($file->getBasename(), array('..'))
                && strnatcmp($this->getPath(), $file->getRealPath())!=0) {
                $resource = new ImportFolderResource($file, $this->files);
                $descendants[$resource->getKey()] = $resource;
            }
        }
        return $descendants;
    }

}