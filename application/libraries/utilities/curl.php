<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: adi
     * Date: 10/29/12
     * Time: 6:31 AM
     * To change this template use File | Settings | File Templates.
     */

namespace Utilities;

class Curl {

    public static function post_json($url, $content) {
        $content = json_encode($content);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 201 ) {
            \Log::info("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }
        curl_close($curl);
        $response = json_decode($json_response, true);

        return $response;
    }

}