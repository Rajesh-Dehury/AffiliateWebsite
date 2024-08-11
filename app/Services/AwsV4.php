<?php

namespace App\Services;

class AwsV4
{
    private $accessKey;
    private $secretKey;
    private $path;
    private $regionName;
    private $serviceName;
    private $httpMethodName;
    private $awsHeaders = [];
    private $payload = '';

    private $HMACAlgorithm = "AWS4-HMAC-SHA256";
    private $aws4Request = "aws4_request";
    private $strSignedHeader = null;
    private $xAmzDate = null;
    private $currentDate = null;

    public function __construct($accessKey, $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->xAmzDate = $this->getTimeStamp();
        $this->currentDate = $this->getDate();
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    }

    public function setRegionName($regionName)
    {
        $this->regionName = $regionName;
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    public function setRequestMethod($method)
    {
        $this->httpMethodName = $method;
    }

    public function addHeader($headerName, $headerValue)
    {
        $this->awsHeaders[$headerName] = $headerValue;
    }

    public function getHeaders()
    {
        $this->awsHeaders['x-amz-date'] = $this->xAmzDate;
        ksort($this->awsHeaders);

        $canonicalURL = $this->prepareCanonicalRequest();
        $stringToSign = $this->prepareStringToSign($canonicalURL);
        $signature = $this->calculateSignature($stringToSign);

        if ($signature) {
            $this->awsHeaders['Authorization'] = $this->buildAuthorizationString($signature);
            return $this->awsHeaders;
        }
    }

    private function prepareCanonicalRequest()
    {
        $canonicalURL = "";
        $canonicalURL .= $this->httpMethodName . "\n";
        $canonicalURL .= $this->path . "\n" . "\n";
        $signedHeaders = '';
        foreach ($this->awsHeaders as $key => $value) {
            $signedHeaders .= $key . ";";
            $canonicalURL .= $key . ":" . $value . "\n";
        }
        $canonicalURL .= "\n";
        $this->strSignedHeader = substr($signedHeaders, 0, -1);
        $canonicalURL .= $this->strSignedHeader . "\n";
        $canonicalURL .= $this->generateHex($this->payload);
        return $canonicalURL;
    }

    private function prepareStringToSign($canonicalURL)
    {
        $stringToSign = '';
        $stringToSign .= $this->HMACAlgorithm . "\n";
        $stringToSign .= $this->xAmzDate . "\n";
        $stringToSign .= $this->currentDate . "/" . $this->regionName . "/" . $this->serviceName . "/" . $this->aws4Request . "\n";
        $stringToSign .= $this->generateHex($canonicalURL);
        return $stringToSign;
    }

    private function calculateSignature($stringToSign)
    {
        $signatureKey = $this->getSignatureKey($this->secretKey, $this->currentDate, $this->regionName, $this->serviceName);
        $signature = hash_hmac("sha256", $stringToSign, $signatureKey, true);
        return strtolower(bin2hex($signature));
    }

    private function buildAuthorizationString($signature)
    {
        return $this->HMACAlgorithm . " " . "Credential=" . $this->accessKey . "/" . $this->getDate() . "/" . $this->regionName . "/" . $this->serviceName . "/" . $this->aws4Request . "," . "SignedHeaders=" . $this->strSignedHeader . "," . "Signature=" . $signature;
    }

    private function generateHex($data)
    {
        return strtolower(bin2hex(hash("sha256", $data, true)));
    }

    private function getSignatureKey($key, $date, $regionName, $serviceName)
    {
        $kSecret = "AWS4" . $key;
        $kDate = hash_hmac("sha256", $date, $kSecret, true);
        $kRegion = hash_hmac("sha256", $regionName, $kDate, true);
        $kService = hash_hmac("sha256", $serviceName, $kRegion, true);
        $kSigning = hash_hmac("sha256", $this->aws4Request, $kService, true);

        return $kSigning;
    }

    private function getTimeStamp()
    {
        return gmdate("Ymd\THis\Z");
    }

    private function getDate()
    {
        return gmdate("Ymd");
    }
}
