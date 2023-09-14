<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveLinksRequest;
use App\Jobs\UrlProcessJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class LinkController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function add() 
    {
        return view('add');
    }

    public function saveLinks(SaveLinksRequest $request)
    {
        try
        {
            $invalids = [];
            $urls = collect(explode(PHP_EOL, $request->urls))->filter(function($value) use(&$invalids){

                // Validating urls, if those are valid structured.
                if (!filter_var($value, FILTER_VALIDATE_URL))
                {
                    $invalids[] = $value;
                    return false;
                }
                return true;

            })->chunk(1000); // Chunk data of 1000 size.

            // Batch initiated and dispatch jobs through batch.
            $batch = Bus::batch([]);
            foreach ($urls as $url)
            {
                $batch->add(new UrlProcessJob($url->toArray()));
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully stored urls.',
                'data' => $batch->dispatch(),
                'invalid_urls' =>  $invalids
            ], 200);
        }
        catch (\Exception $e)
        {
            Log::error("Error to store urls", [$e]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error to store urls',
                'data' => null
            ], 400);
        }
    }
}
