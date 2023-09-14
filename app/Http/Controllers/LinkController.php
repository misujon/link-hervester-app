<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveLinksRequest;
use App\Jobs\UrlProcessJob;
use App\Services\UrlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class LinkController extends Controller
{
    private $urlService;
    public function __construct(UrlService $service)
    {
        $this->urlService = $service;
    }

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

                if (!filter_var($value, FILTER_VALIDATE_URL))
                {
                    $invalids[] = $value;
                    return false;
                }
                return true;

            })->chunk(2);

            // $batch = Bus::batch([])->dispatch();
            foreach ($urls as $url)
            {
                $this->urlService->saveUrls($url->toArray());
                // $batch->add(new UrlProcessJob($url->toArray()));
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully stored urls.',
                // 'data' => $batch,
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
