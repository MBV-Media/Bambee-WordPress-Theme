<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace Lib;


use Inc\Bambee;

/**
 * The class representing both website (user frontend) and WordPress admin.
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
class CustomBambee extends Bambee {

    /**
     * @since 1.0.0
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }
}