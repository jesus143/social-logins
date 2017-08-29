<?php

/**
 * Created by Sonu Kumar (sonu29392@gmail.com)
 * User: RAPS-Developer
 * Date: 7/27/2017
 * Time: 1:25 PM
 */
$client_id = $_GET['id'];
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=UserData.csv');
// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('S No.', 'Name', 'Email', 'DOB'));
// fetch the data
// loop over the rows, outputting them
$i = 1;
//$file ='../'.$client_id . '_*.json';
foreach (glob('../' . $client_id . '_*.json') as $file) {
    foreach (file($file) as $line) {
        $json = json_decode($line, TRUE);
        {
            $full_name = $network = $location = $gender = $age = $contact = $email = '';
            $network = isset($json['network']) ? $json['network'] : "";
            if (isset($json['name']['familyName'])) {
                $full_name = $json['displayName'];
            } else {
                $full_name = $json['name'];
            }
            if ($network == 'facebook') {
                $full_name = $json['name'];
                $email = $json['email'];
                $birthday = isset($json['birthday']) ? $json['birthday'] : "";
                if (!empty($birthday)) {
                    $birthyear = new DateTime($birthday);
                    $currentYear = new DateTime('today');
                    $age = $birthyear->diff($currentYear)->y;
                }
                $gender = strtolower($json['gender']);
                if (isset($json['location']) && is_array($json['location'])) {
                    $location = $json['location']['name'];
                }
            }
            if ($network == 'twitter') {
                $full_name = $json['name'];
                $location = $json['location'];
                $email = $json['email'];
                if (isset($json['profile_image_url']) && !empty($json['profile_image_url'])) {
                    $image = $json['profile_image_url'];
                    $image = str_replace('_normal', '', $image);
                }
            }
            if ($network == 'googleplus') {
                $full_name = $json['displayName'];
                $email = $json['email'];
                $gender = strtolower($json['gender']);
                if (isset($json['placesLived']) && is_array($json['placesLived'])) {
                    $location = end($json['placesLived'])['value'];
                }
                if (isset($json['ageRange']) && is_array($json['ageRange'])) {
                    $age = $json['ageRange']['min'];
                }
            }
            if ($network == 'linkedin') {
                $full_name = $json['formattedName'];
                $email = $json['email'];
                $location = isset($json['location']['name']) ? $json['location']['name'] : "";
                $image = isset($json['profile_picture']) ? $json['profile_picture'] : "";
            }
            $out = array(
                "S.No" => $i,
                "Name" => $full_name,
                "Email" => $email,
                "DOB" => $age
            );

            fputcsv($output, $out);
        }
    }
    $i++;
}