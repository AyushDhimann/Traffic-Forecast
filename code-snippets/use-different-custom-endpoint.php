<?php

# Creating the custom endpoint file
// To use a different custom endpoint file location, first create a file at your preferred location and copy over the contents from the file linked below
// file: https://github.com/Trailblazers/Traffic-Forecast/blob/master/Traffic-Forecast-collect.php

# Fix path references
// In the file, fix the relative file paths to the uploads directory and the functions.php file from the wp-content/plugins/Traffic-Forecast directory.

# Define a constant
// To tell Traffic Forecast of your custom endpoint, define the following constant:
define( 'Traffic_Forecast_CUSTOM_ENDPOINT', '/path-to-your-custom-endpoint-file.php' );

# Test the result
// Finally, ensure the file is accessible through your web server and that Traffic Forecast is able to use it correctly.
