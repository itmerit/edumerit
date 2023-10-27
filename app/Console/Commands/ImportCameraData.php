<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportCameraData extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:camera-data';
    protected $description = 'Import data from the face recognition camera API';

    public function handle()
    {
                        Log::info('API Response:', ['response' => now()->hour]);

        $startHour = 7;
        $endHour = 19;
        $searchResultPosition = 0;

        $client = new Client([
            'auth' => [
                'admin',
                '12345678a',
                'digest',
            ],
        ]);

        while (now()->hour >= 1 && now()->hour <= 10) {
            // Build your API request data and endpoint URL.
            $page = 0;
            do {
                $response = $client->post('http://192.168.100.9/ISAPI/AccessControl/AcsEvent?format=json', [
                    'json' => [
                        "AcsEventCond" => [
                            "searchID" => $page. "-page",
                            "searchResultPosition" => $searchResultPosition + 30,
                            "maxResults" => 30,
                            "major" => 0,
                            "minor" => 0,
                            "startTime" => "2023-10-25T06:00:00+05:00",
                            "endTime"=> "2023-10-25T19:00:00+05:00"
                        ],
                    ],
                ]);
                $data = json_decode($response->getBody(), JSON_PRETTY_PRINT);

                Log::channel('daily')->info('API Response', [
                    'data' => $data
                ]);
                // Process the API response and save data to the database.

                $page += 1; // Update the page for the next request.
                $searchResultPosition += 30;

                sleep(10); // Wait for 15 seconds before the next request.
            } while ($searchResultPosition < $data['AcsEvent']['totalMatches']); // Adjust this based on the API response structure.
        }
//
//        $page = 1;
//        $searchResultPosition = 30;
//
//        // Define your API request here
//        do {
//            // Construct and send the API request with the appropriate parameters and data.
//            // Use Guzzle or another HTTP client library for making HTTP requests.
//
//            $response = $client->post('http://192.168.100.9/ISAPI/AccessControl/AcsEvent?format=json', [
//                'json' => [
//                    "AcsEventCond" => [
//                        "searchID" => $page. "-page",
//                        "searchResultPosition" => $searchResultPosition + 30,
//                        "maxResults" => 30,
//                        "major" => 0,
//                        "minor" => 0,
//                        "startTime" => "2023-10-25T06:00:00+05:00",
//                        "endTime"=> "2023-10-25T19:00:00+05:00"
//                    ],
//                ],
//            ]);
//
//            if ($response->getStatusCode() === 200) {
//                $data = json_decode($response->getBody(), JSON_PRETTY_PRINT);
//
//                $responseStatusStrg = $data['AcsEvent']['responseStatusStrg'];
//
//                // Process and import data into your database here.
//
//                $page++; // Move to the next page.
//                $searchResultPosition += 30;
//                Log::info('API Response:', ['response' => $data]);
//
//            } else {
//                // Handle API request failures
//                $this->error('API request failed.');
//            }
//        } while ($searchResultPosition == "OK");
//
//        dd('finished');

        // Check if the API request was successful
//        if ($response->successful()) {
//            $data = $response->json();
//
//            // Save the data to a file
//            Storage::put('camera_data.json', json_encode($data));
//        } else {
//            // Handle API request failures
//            $this->error('API request failed.');
//        }
    }
}
