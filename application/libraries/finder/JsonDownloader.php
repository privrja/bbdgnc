<?php

namespace Bbdgnc\Finder;

abstract class JsonDownloader {

    /**
     * Download JSON file from URI and decode it to array
     * @param string $strUri uri for curl
     * @return bool|array false if something goes wrong or array with result when ok
     * @throws \Exception
     */
    public static function getJsonFromUri($strUri) {
        $curl = curl_init($strUri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            /** TODO Bad transfer -> maybe can try it again? */
            $error = curl_error($curl);
            curl_close($curl);
            log_message("error", "Error in cURL on URI: " . $strUri);
            log_message("error", "Error in cURL: " . $error);
            throw new \Exception("Error during cURL");
        }
        curl_close($curl);
        $decoded = json_decode($curl_response, true);

        /* Bad reply */
        if (isset($decoded[PubChemFinder::REPLY_FAULT])) {
            log_message('error', "REST reply fault. Uri: " . $strUri);
            return false;
        }

        log_message('info', "Response OK to URI: $strUri");
        return $decoded;
    }

}