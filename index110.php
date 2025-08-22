<?php

$url = 'https://super-fog-7140.bohmoh280.workers.dev/api/matches/';

// Initialize cURL session
$curl = curl_init();

// Set cURL options
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the transfer as a string of the return value
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // This is not recommended in production, but might be needed for some local setups. 

// Execute cURL request
$response = curl_exec($curl);

// Check for cURL errors
if (curl_errno($curl)) {
    $error_msg = curl_error($curl);
    echo "cURL Error: " . $error_msg;
} else {
    // Decode the JSON response
    $data = json_decode($response, true); // true to get an associative array

    // Check if JSON decoding was successful
    if ($data === null) {
        echo "Error decoding JSON.";
    } else {
        // Now you can work with the data. For example, print it out.
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

// Close cURL session
curl_close($curl);

?>
