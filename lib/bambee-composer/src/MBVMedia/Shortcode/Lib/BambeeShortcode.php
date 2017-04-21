<?php

namespace MBVMedia\Shortcode\Lib;


abstract class BambeeShortcode implements Handleable {

    /**
     * @var array
     */
    private $supportedAtts;

    /**
     * @var string
     */
    private $description;

    /**
     * BambeeShortcode constructor.
     */
    public function __construct() {
        $this->supportedAtts = array();
    }

    /**
     * @return array
     */
    public function getSupportedAtts() {
        return $this->supportedAtts;
    }

    /**
     * @param $name
     * @param string $default
     * @param string $type TinyMCE input type
     */
    public function addAttribute( $name, $default = '', $type = 'text' ) {
//        $this->supportedAtts[$name] = $default;
        $this->supportedAtts[] = array(
                'name' => $name,
                'default' => $default,
                'type' => $type
        );
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param $description
     */
    public function setDescription( $description ) {
        $this->description = $description;
    }

    /**
     *
     */
    public static function addShortcode() {

        $tag = static::getShortcodeAlias();

        $class = get_called_class();
        if ( empty( $tag ) ) {
            $tag = self::getUnqualifiedClassName( $class );
        }

        add_shortcode( $tag, array( $class, 'doShortcode' ) );
    }

    /**
     * @param array $atts
     * @param string $content
     * @return mixed
     */
    public static function doShortcode( $atts = array(), $content = '' ) {
        $shortcodeObject = new static();
        $supportedAtts = $shortcodeObject->getSupportedAtts();
        $defaultAtts = array();
        foreach ( $supportedAtts as $attribute ) {
            $defaultAtts[$attribute['name']] = $attribute['default'];
        }

        /* TODO: Add shortcode name as argument to shortcode_atts */
        $atts = shortcode_atts( $defaultAtts, $atts );

        return do_shortcode( $shortcodeObject->handleShortcode( $atts, $content ) );
    }

    /**
     * @return string
     */
    public static function getShortcodeAlias() {
        return self::getUnqualifiedClassName();
    }

    /**
     * @return string
     */
    public static function getUnqualifiedClassName( $class = null ) {
        if ( $class === null ) {
            $class = get_called_class();
        }
        $reflect = new \ReflectionClass( $class );
        return strtolower( $reflect->getShortName() );
    }
}