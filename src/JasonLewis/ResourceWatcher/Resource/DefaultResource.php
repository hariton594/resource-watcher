<?php
/**
 * Created by IntelliJ IDEA.
 * User: shari
 * Date: 30.01.2018
 * Time: 21:24
 */

namespace JasonLewis\ResourceWatcher\Resource;


class DefaultResource implements ResourceInterface
{

    /**
     * Detect any changes to the resource.
     *
     * @return array
     */
    public function detectChanges()
    {
        return [];
    }
}