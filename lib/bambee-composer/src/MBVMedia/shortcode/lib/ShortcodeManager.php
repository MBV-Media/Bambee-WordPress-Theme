<?php
/**
 * Created by PhpStorm.
 * User: hterhoeven
 * Date: 28.04.2016
 * Time: 10:55
 */

namespace MBVMedia\Shortcode\Lib;


class ShortcodeManager {

    /**
     * @var array
     */
    private $shortcodeList;

    /**
     * ShortocdeManager constructor.
     */
    public function __construct() {
        $this->shortcodeList = array();
    }

    /**
     * @param array $locationInfo
     */
    public function collectShortcodes( array $locationInfo ) {

        $shortcodeDir = scandir( $locationInfo['path'] );

        foreach ( $shortcodeDir as $shortcodeFile ) {
            if ( !is_dir( $locationInfo['path'] . $shortcodeFile ) ) {

                $index = count( $this->shortcodeList );
                $class = $locationInfo['namespace'] . pathinfo( $shortcodeFile, PATHINFO_FILENAME );

                $this->shortcodeList[$index]['class'] = $class;
                $this->shortcodeList[$index]['file'] = $shortcodeFile;
                $this->shortcodeList[$index]['tag'] = $class::getUnqualifiedClassName( $class );
            }
        }
    }

    /**
     *
     */
    public function loadShortcodes() {
        foreach ( $this->shortcodeList as $shortcode ) {
            $class = $shortcode['class'];
            if ( is_callable( array( $class, 'addShortcode' ) ) ) {
                $class::addShortcode();
            }
        }
    }

    public function addTinyMCEPlugin() {
        foreach ( $this->shortcodeList as $shortcode ) {
            $class = $shortcode['class'];
            if ( is_callable( array( $class, 'addTinyMCEPlugin' ) ) ) {
                $class::addTinyMCEPlugin();
            }
        }
    }
}