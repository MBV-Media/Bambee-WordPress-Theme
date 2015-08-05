<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace Inc;


/**
 * The class representing a view.
 *
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 *
 * @example
 *  Usage:
 *    $template = new ThemeView( 'path/to/view.php', array(
 *      'param1' => 'This is passed to the view as $param1'
 *    ) );
 *    echo $template->render()
 */
class ThemeView {

    /**
     * @var array
     * @since 1.0.0
     */
    private $args;

    /**
     * @var string
     * @since 1.0.0
     */
    private $file;

    /**
     * @since 1.0.0
     * @param string $file
     * @param array $args
     * @return void
     */
    public function __construct( $file, $args = array() ) {
        $this->file = $file;
        $this->args = $args;
    }

    /**
     * Render the view.
     *
     * @since 1.0.0
     * @return string
     */
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