// First you need put a listener on the page, the principal 

// This approach is inelegant, I simply placed listener in main activity page plugins\youzify\includes\public\templates\activity\index.php

<html>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Establecer la cookie al hacer clic en 'Temuco'.
    document.getElementById('Custom Filter').addEventListener('click', function() {
        document.cookie = "temuco_filter=true; path=/";
        location.reload(); // Recargar la página para aplicar el filtro.
    });

    // Eliminar la cookie al hacer clic en 'All Members'.
    document.getElementById('activity-all').addEventListener('click', function() {
        document.cookie = "custom_filter=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
        location.reload(); // Recargar la página para aplicar el cambio.
    });
});
</script>
</html>

This is how I trigger a cookie with the filter when someone clicks, I put this on functions.php:

<?php
function custom_filter_activities_by_location( $activities, $args ) {
    // Check if the 'temuco' filter is selected via the session cookie.
    if ( isset( $_COOKIE['temuco_filter'] ) && $_COOKIE['custom_filter'] === 'true' ) {
        // Filter the activities based on the user's location.
        foreach ( $activities['activities'] as $key => $activity ) {
            $user_location = xprofile_get_field_data( 16, $activity->user_id );
            if ( $user_location !== 'Options_on_profile' ) {
                unset( $activities['activities'][ $key ] );
            }
        }
        $activities['activities'] = array_values( $activities['activities'] ); // Reindex array.
    }
    return $activities;
}
add_action( 'bp_activity_get', 'custom_filter_activities_by_location', 10, 2 ); ?>


When the person clicks on "all" the cookie is deleted


It is not the same as a filter built into Youzify, in this approach the page reloads each time with the new filter. But at least it works
