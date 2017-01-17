<?php
/**
 * @since 1.0.0
 * @author hterhoeven
 * @licence MIT
 */

namespace MBVMedia\Shortcode;


use MBVMedia\Shortcode\Lib\BambeeShortcode;

class AspectRatio extends BambeeShortcode{

    /**
     * @var array
     */
    private $predefinedRatioList;

    /**
     * @var string
     */
    private $ratio;

    public function __construct() {
        $this->addAttribute( 'ratio', '16:9' );
        $this->addAttribute( 'class' );

        $this->predefinedRatioList = array(
            'square' => 'square',
            '1:1' => 'square',
            '2:1' => '2to1',
            '16:9' => '16to9',
            '4:3' => '4to3',
            '1:2' => '1to2',
            '3:4' => '3to4',
            '9:16' => '9to16',
        );

        $this->ratio = '';
    }

    public function handleShortcode( array $atts = array(), $content = '' ) {

        $this->ratio = $atts['ratio'];

        $ratioClass = $this->getRatioClass();

        try {
            $ratioStyle = empty( $ratioClass ) ? $this->getRatioStyle() : '';
        }
        catch( \InvalidArgumentException $e ) {
            return $e->getMessage();
        }

        $class = empty( $atts['class'] ) ? '' : ' ' . $atts['class'];

        $output  = '<div class="responsive-aspect-ratio%s"%s>';
        $output .= '    <div class="aspect-ratio-content%s">';
        $output .= '        ' . $content;
        $output .= '    </div>';
        $output .= '</div>';

        $output = sprintf( $output, $ratioClass, $ratioStyle, $class );

        return $output;
    }

    public static function getShortcodeAlias() {
        return 'aspect-ratio';
    }


    /**
     * @return array
     */
    public function &getPredefinedRatioList() {
        return $this->predefinedRatioList;
    }

    /**
     * @throws \InvalidArgumentException
     * @return string
     */
    private function getRatioClass() {
        return isset( $this->predefinedRatioList[$this->ratio] )
            ? ' ratio-' . $this->predefinedRatioList[$this->ratio]
            : '';
    }

    /**
     * @return string
     */
    private function getRatioStyle() {
        return ' style="padding-top: ' . $this->calculatePadding() . '%;"';
    }

    /**
     * @throws \InvalidArgumentException
     * @return float
     */
    private function calculatePadding() {
        $ratio = explode( ':', $this->ratio );

        if ( count( $ratio ) !== 2 ) {
            throw new \InvalidArgumentException( __( 'Not a valid ratio.', TextDomain ) );
        }

        return $ratio[1] / $ratio[0] * 100;
    }
}