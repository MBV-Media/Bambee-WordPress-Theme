<div class="responsive-aspect-ratio ratio-16to9">
    <div class="aspect-ratio-content">
        <div id="map" style="height: 100%;"></div>
    </div>
</div>

<script type="text/javascript">

    function initMap() {
        <?php $lat = get_option( 'bambee_google_maps_latitude' ); ?>
        <?php $lng = get_option( 'bambee_google_maps_longitude' ); ?>
        var latLng = {
            lat: <?php echo empty( $lat ) ? 0 : $lat; ?>,
            lng: <?php echo empty( $lng ) ? 0 : $lng; ?>
        };

        <?php $styles = get_option( 'bambee_google_maps_styles' ); ?>
        var styles = <?php echo empty( $styles ) ? '[]' : $styles; ?>;

        var map = new google.maps.Map(document.getElementById('map'), {
            center: latLng,
            zoom: <?php echo get_option( 'bambee_google_maps_zoom', 15 ); ?>,
            styles: styles,
            disableDefaultUI: true,
            gestureHandling: 'cooperative'
        });

        var marker = new google.maps.Marker({
            position: latLng,
            map: map
        });
    }

    // Wait for google maps to initialize until all images are loded.
    jQuery(window).on( 'load', (function() {
        jQuery.getScript('https://maps.googleapis.com/maps/api/js?key=<?php echo get_option( 'bambee_google_maps_api_key' ); ?>&libraries=places&callback=initMap');
    }));

</script>
