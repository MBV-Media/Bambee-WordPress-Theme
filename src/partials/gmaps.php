<div class="responsive-aspect-ratio ratio-16to9">
    <div class="aspect-ratio-content">
        <div id="map" style="height: 100%;"></div>
    </div>
</div>

<script type="text/javascript">

    function initMap() {
        <?php $lat = get_option( 'bambee_google_maps_latitude_setting', true ); ?>
        <?php $lng = get_option( 'bambee_google_maps_longitude_setting', true ); ?>
        latLng = {
            lat: <?php echo empty( $lat ) ? 0 : $lat; ?>,
            lng: <?php echo empty( $lng ) ? 0 : $lat; ?>
        };

        <?php $styles = get_option( 'bambee_google_maps_styles_setting', true ); ?>
        var styles = <?php echo empty( $styles ) ? '[]' : $styles; ?>;

        var map = new google.maps.Map(document.getElementById('map'), {
            center: latLng,
            zoom: <?php echo get_option( 'bambee_google_maps_zoom_setting', true ); ?>,
            styles: styles,
            disableDefaultUI: true
        });
    }

</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo get_option( 'bambee_google_maps_api_key_setting' ); ?>&libraries=places&callback=initMap">
</script>
