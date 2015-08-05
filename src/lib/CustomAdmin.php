<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace Lib;


use Inc\BambeeAdmin;

/**
 * The class representing the WordPress Admin.
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
class CustomAdmin extends BambeeAdmin {

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }
}