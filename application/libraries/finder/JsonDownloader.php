<?php

namespace Bbdgnc\Finder;

use Bbdgnc\Base\Logger;
use Bbdgnc\Enum\LoggerEnum;
use Bbdgnc\Finder\Exception\BadTransferException;

abstract class JsonDownloader {

    /**
     * Download JSON file from URI and decode it to array
     * @param string $strUri uri for curl
     * @return bool|array false if something goes wrong or array with result when ok
     * @throws BadTransferException
     */
    public static function getJsonFromUri($strUri) {
        $curl = curl_init($strUri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        ini_set('max_execution_time', 40);
        $curl_response = curl_exec($curl);
        ini_set('max_execution_time', 30);
        if ($curl_response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            Logger::log(LoggerEnum::ERROR, "Error in cURL on URI: " . $strUri);
            Logger::log(LoggerEnum::ERROR, "Error in cURL: " . $error);
            throw new BadTransferException("Error during cURL");
        }
        curl_close($curl);
        $decoded = json_decode($curl_response, true);

        /* Bad reply */
        if (isset($decoded[PubChemFinder::REPLY_FAULT])) {
            Logger::log(LoggerEnum::ERROR, "REST reply fault. Uri: " . $strUri);
            return false;
        }

        Logger::log(LoggerEnum::INFO, "Response OK to URI: $strUri");
        return $decoded;
    }

}
