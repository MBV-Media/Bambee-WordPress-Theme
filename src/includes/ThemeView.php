<?php namespace Inc;


class ThemeView {
    private $args;
    private $file;

    public function __construct( $file, $args = array() ) {
        $this->file = $file;
        $this->args = $args;
    }

    public function render() {
        extract( $this->args );
        ob_start();
        if ( locate_template( $this->file ) ) {
            require( locate_template( $this->file ) );
        }
        $templatePart = ob_get_clean();

        return $templatePart;
    }
}