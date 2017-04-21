<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\ThemeCustomizer;


abstract class ThemeCustommizerElement {

    private $id;

    private $args;

    public function __construct( $id, array $args ) {
        $this->id = $id;
        $this->args = $args;
    }

    public function getId() {
        return $this->id;
    }

    public function getArgs() {
        return $this->args;
    }

    public function setArg( $name, $value ) {
        $this->args[$name] = $value;
    }

    public abstract function register( \WP_Customize_Manager $wpCustomize );
}