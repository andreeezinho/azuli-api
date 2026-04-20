<?php

namespace App\Infra\Services\Xml;

use App\Infra\Services\Log\LogService;

class XmlService {

    public function saveXml(string $encodedFile){
        try {
            $decodeXml = base64_decode($encodedFile);

            $xmlNFe = gzdecode($decodeXml);

            $xmlHashedName = uniqid() . "_" . time() . '.xml';

            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/public/NFe/Xml/'. $xmlHashedName, $xmlNFe);

            return $xmlHashedName;
        }catch (\Exception $e) {
            LogService::logError($e->getMessage());
            return null;
        }
    }

    public function convertXmltoArray(mixed $xml){
        try {
            $xmlString = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/public/NFe/Xml/'. $xml);

            $xml = simplexml_load_string($xmlString);

            return $this->convert($xml);
        }catch (\Exception $e) {
            LogService::logError($e->getMessage());
            return null;
        }
    }

    private function convert(mixed $xml){
        $result = [];

        foreach ($xml as $key => $value) {
            $convertedValue = ($value->count() > 0) ? $this->convert($value) : (string) $value;

            if (!isset($result[$key])) {
                $result[$key] = $convertedValue;
                continue;
            }

            if (!is_array($result[$key]) || !array_is_list($result[$key])) {
                $result[$key] = [$result[$key]];
            }

            $result[$key][] = $convertedValue;
        }

        foreach ($xml->attributes() as $attrKey => $attrValue) {
            $result['@attributes'][$attrKey] = (string) $attrValue;
        }

        return $result;
    }

}