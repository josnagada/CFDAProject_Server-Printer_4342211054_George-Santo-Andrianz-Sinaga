<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
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

        $client = new Client();

        try {
            $response = $client->post('https://api.cloudconvert.com/v2/convert', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('CLOUDCONVERT_API_KEY'),
                ],
                'multipart' => [
                    [
                        'name' => 'input',
                        'contents' => fopen($filePath, 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ],
                    [
                        'name' => 'outputformat',
                        'contents' => 'pdf',
                    ],
                    [
                        'name' => 'wait',
                        'contents' => 'true',
                    ],
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            if (isset($result['data'])) {
                $pdfUrl = $result['data']['file']['url'];
                $pdfContent = file_get_contents($pdfUrl);

                $pdfPath = 'pdf/' . $fileName . '.pdf';
                Storage::disk('public')->put($pdfPath, $pdfContent);

                return response()->download(storage_path("app/public/{$pdfPath}"));
            } else {
                return response()->json(['error' => 'Conversion failed.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}