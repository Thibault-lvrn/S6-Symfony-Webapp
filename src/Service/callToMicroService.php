<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CallToMicroService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function generatePdfFromHtml()
    {
        // $htmlContent = file_get_contents($htmlFilePath);

        try {
            $response = $this->httpClient->request('GET', 'http://127.0.0.1:8088/S6-MicroService/public/index.php/pdf/generation');

            if ($response->getStatusCode() == 200) {

                $pdfContent = $response->getContent();

                // $pdfFilePath = '../public/google.pdf';

                // file_put_contents($pdfFilePath, $pdfContent);

                return $pdfContent;
            }
            else {
                return 'Unexpected HTTP status: ' . $response->getStatusCode() . ' ' . $response->getInfo('http_code');
            }
        } catch (TransportExceptionInterface $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
