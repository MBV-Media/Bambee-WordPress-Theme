<?php
/**
 * @since 1.0.0
 * @author R4c00n <marcel.kempf93@gmail.com>
 * @licence MIT
 */
namespace Lib;


use MBVMedia\BambeeAdmin;

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
    public function __construct( CustomBambee $bambee ) {
        parent::__construct( $bambee );
    }

    /**
     * @since 1.4.2
     *
     * @param CustomBambee $bambee
     */
    public static function run( CustomBambee $bambee ) {
        global $bambeeWebsite;

        $bambeeWebsite = new CustomAdmin( $bambee );
        $bambeeWebsite->addGlobalDataField('test', array(
            'type' => 'label',
            'title' => 'Shortcodes',
            'description' => __(
                'You can use the [globaldata]key[globaldata] '.
                ' shortcode to display the global data field inside a post.',
                TextDomain
            ),
        ));
    }
}