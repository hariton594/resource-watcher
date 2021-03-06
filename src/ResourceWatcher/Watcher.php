<?php namespace ResourceWatcher;

use Closure;
use ResourceWatcher\Resource\DefaultResourceCreator;
use ResourceWatcher\Resource\ResourceCreatorInterface;
use SplFileInfo;
use RuntimeException;
use \ResourceWatcherFilesystemHelper;
use ResourceWatcher\Resource\FileResource;
use ResourceWatcher\Resource\DirectoryResource;

class Watcher
{
    /**
     * Tracker instance.
     *
     * @var \\ResourceWatcher\Tracker
     */
    protected $tracker;

    /**
     * Illuminate filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    protected $resourceCreator;

    /**
     * Indicates if the watcher is watching.
     *
     * @var bool
     */
    protected $watching = false;

    private static $_instance = null;

    private function __construct() {
        $this->tracker = Tracker::getInstance();
        $this->files = new FilesystemHelper();

        $this->resourceCreator = new DefaultResourceCreator($this->files);
    }

    protected function __clone() {
    }

    static public function getInstance() {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function setResourceCreator(ResourceCreatorInterface $resourceCreator) {
        $this->resourceCreator = $resourceCreator;
    }

    /**
     * Register a resource to be watched.
     *
     * @param  string  $resource
     * @return \\ResourceWatcher\Listener
     */
    public function watch($resource)
    {
        if (! $this->files->exists($resource)) {
            throw new RuntimeException('Resource must exist before you can watch it.');
        }

        if ($this->files->isDirectory($resource)) {
            $resource = $this->resourceCreator->createDirectory(new SplFileInfo($resource), $this->files);
            $resource->setupDirectory();
        } else {
            $resource = $this->resourceCreator->createFile(new SplFileInfo($resource), $this->files);
        }

        // The listener gives users the ability to bind listeners on the events
        // created when watching a file or directory. We'll give the listener
        // to the tracker so the tracker can fire any bound listeners.
        $listener = new Listener;

        $this->tracker->register($resource, $listener);

        return $listener;
    }

    /**
     * Start watching for a given interval. The interval and timeout and measured
     * in microseconds, so 1,000,000 microseconds is equal to 1 second.
     *
     * @param  int  $interval
     * @param  int  $timeout
     * @param  \Closure  $callback
     * @return void
     */
    public function startWatch($interval = 1000000, $timeout = null, Closure $callback = null)
    {
        $this->watching = true;

        $timeWatching = 0;

        while ($this->watching) {
            if (is_callable($callback)) {
                call_user_func($callback, $this);
            }

            usleep($interval);

            $this->tracker->checkTrackings();

            $timeWatching += $interval;

            if (! is_null($timeout) && $timeWatching >= $timeout) {
                $this->stopWatch();
            }
        }
    }

    /**
     * Alias of startWatch.
     *
     * @param  int  $interval
     * @param  int  $timeout
     * @param  \Closure  $callback
     * @return void
     */
    public function start($interval = 1000000, $timeout = null, Closure $callback = null)
    {
        $this->startWatch($interval, $timeout, $callback);
    }

    /**
     * Get the tracker instance.
     *
     * @return \\ResourceWatcher\Tracker
     */
    public function getTracker()
    {
        return $this->tracker;
    }

    /**
     * Stop watching.
     *
     * @return void
     */
    public function stopWatch()
    {
        $this->watching = false;
    }

    /**
     * Alias of stopWatch.
     *
     * @return void
     */
    public function stop()
    {
        $this->stopWatch();
    }

    /**
     * Determine if watcher is watching.
     *
     * @return bool
     */
    public function isWatching()
    {
        return $this->watching;
    }
}
