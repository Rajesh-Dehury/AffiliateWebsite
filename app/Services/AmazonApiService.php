<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AmazonApiService
{
    private string $accessKey;
    private string $secretKey;
    private string $partnerTag;
    private string $region = 'eu-west-1';
    private string $host = 'webservices.amazon.in';
    private string $uriPath = '/paapi5/getitems';

    public function __construct()
    {
        $this->accessKey  = (string) config('services.amazon.access_key', env('AMAZON_ACCESS_KEY'));
        $this->secretKey  = (string) config('services.amazon.secret_key', env('AMAZON_SECRET_KEY'));
        $this->partnerTag = (string) config('services.amazon.partner_tag', env('AMAZON_PARTNER_TAG', 'codewithrd-21'));
    }

    public function getItems(array $asins): array
    {
        if (empty($asins)) {
            return [];
        }

        $awsService = new AwsV4($this->accessKey, $this->secretKey);
        $awsService->setRegionName($this->region);
        $awsService->setServiceName('ProductAdvertisingAPI');
        $awsService->setPath($this->uriPath);
        $awsService->setRequestMethod('POST');

        $payload = json_encode([
            'ItemIds' => array_values($asins),
            'Resources' => [
                'Images.Primary.Large',
                'ItemInfo.Title',
                'Offers.Listings.Price',
                'Offers.Listings.SavingBasis',
                'Offers.Listings.Availability.Type',
            ],
            'PartnerTag' => $this->partnerTag,
            'PartnerType' => 'Associates',
            'Marketplace' => 'www.amazon.in'
        ]);

        $awsService->setPayload($payload);
        $awsService->addHeader('content-encoding', 'amz-1.0');
        $awsService->addHeader('content-type', 'application/json; charset=utf-8');
        $awsService->addHeader('host', $this->host);
        $awsService->addHeader('x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.GetItems');

        $headers = $awsService->getHeaders();

        $client = new Client([
            'base_uri' => 'https://' . $this->host,
            'verify' => false 
        ]);

        try {
            $response = $client->post($this->uriPath, [
                'headers' => $headers,
                'json' => json_decode($payload, true)
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            
            if (isset($data['Errors'])) {
                Log::error('Amazon API Business Error', ['errors' => $data['Errors']]);
            }

            return $data['ItemsResult']['Items'] ?? [];
        } catch (\Exception $e) {
            Log::error('Amazon PA-API exception', [
                'message' => $e->getMessage(),
                'payload' => $payload
            ]);
            return [];
        }
    }
}
