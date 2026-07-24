<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class ControllerFile extends Controller
{
    public function convertToPdfApi(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:txt,doc,docx,jpg,jpeg,png'
    ]);

    $file = $request->file('file');
    $filePath = $file->getPathname();
    $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $extension = $file->getClientOriginalExtension();

    $client = new \GuzzleHttp\Client();
    $apiKey = env('CLOUDCONVERT_API_KEY');

    try {
        // Step 1: Create a job
        $response = $client->post('https://api.cloudconvert.com/v2/jobs', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'tasks' => [
                    'import-1' => [
                        'operation' => 'import/upload'
                    ],
                    'task-1' => [
                        'operation' => 'convert',
                        'input' => 'import-1',
                        'output_format' => 'pdf',
                    ],
                    'export-1' => [
                        'operation' => 'export/url',
                        'input' => 'task-1'
                    ]
                ]
            ]
        ]);

        $job = json_decode($response->getBody(), true);
        $uploadUrl = $job['data']['tasks'][0]['result']['form']['url'];
        $parameters = $job['data']['tasks'][0]['result']['form']['parameters'];

        // Step 2: Upload the file
        $multipart = [];
        foreach ($parameters as $key => $value) {
            $multipart[] = [
                'name' => $key,
                'contents' => $value
            ];
        }
        $multipart[] = [
            'name' => 'file',
            'contents' => fopen($filePath, 'r'),
            'filename' => $file->getClientOriginalName()
        ];

        $response = $client->post($uploadUrl, [
            'multipart' => $multipart
        ]);

        // Step 3: Wait for the job to finish and get the result
        $response = $client->get("https://api.cloudconvert.com/v2/jobs/{$job['data']['id']}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ]
        ]);

        $job = json_decode($response->getBody(), true);
        $exportTask = array_values(array_filter($job['data']['tasks'], function ($task) {
            return $task['name'] === 'export-1';
        }))[0];
        $fileUrl = $exportTask['result']['files'][0]['url'];

        // Download the PDF
        $pdfContent = file_get_contents($fileUrl);
        $pdfPath = 'pdf/' . $fileName . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdfContent);

        return response()->download(storage_path("app/public/{$pdfPath}"));
    } catch (\Exception $e) {
        return redirect()->back()->with(['notifikasi' => $e->getMessage(), 'type' => 'error'], 500);
    }
}

}