<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WordsApiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('WORDS_API_URL'),
            'headers' => [
                'X-RapidAPI-Key' => env('WORDS_API_KEY'),
                'X-RapidAPI-Host' => 'wordsapiv1.p.rapidapi.com',
            ],
        ]);
    }

    public function getWordDetails(string $word)
    {
        try {
            $response = $this->client->get("/words/{$word}");
            return json_decode($response->getBody()->getContents(), true);
        } catch (Exception $e) {
            return [
                'error' => 'Unable to fetch data.',
                'message' => $e->getMessage(),
            ];
        }
    }
}
