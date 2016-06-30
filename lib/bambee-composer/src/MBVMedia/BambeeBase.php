<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia;


abstract class BambeeBase {

    /**
     * This is the place where Wordpress actions should be added.
     *
     * @return void
     */
    abstract public function addActions();

    /**
     * This is the place where Wordpress filters should be added.
     *
     * @return void
     */
    abstract public function addFilters();
}