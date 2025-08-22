<?php

$url = 'https://webws.365scores.com/web/games/myscores/?langId=27&timezoneName=UTC&games=4494751,4494750,4458909,4494747,4515179,4494753,4494752,4513695,4513696,4513699,4506649,4513700,4506648,4513694,4458907,4443291,4528352,4458908,4506146,4443289,4528351,4464070,4505005,4452818,4460789,4490881,4490882,4464074,4444986,4444981,4464911,4444984,4464072,4529380,4452819,4464271,4464916,4452824,4452930,4452827,4464917,4452825,4529381,4464910,4490880,4458906,4490879,4464915,4506145,4467559,4464912,4458905,4506143,4490878,4444985,4469113,4469116,4318786';

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
