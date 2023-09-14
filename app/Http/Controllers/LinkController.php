<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveLinksRequest;
use App\Jobs\UrlProcessJob;
use App\Models\Domain;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class LinkController extends Controller
{
    public function index()
    {
        $dataUrl = route('fetchDomains');
        return view('home', compact('dataUrl'));
    }

    public function fetchDomains(Request $request)
    {
        $domains = Domain::leftJoin('links', 'domains.id', '=', 'links.domain_id')
                            ->select('domains.*', DB::raw('count(links.id) as link_count'))
                            ->groupBy('domains.id', 'domains.domain_name', 'domains.created_at', 'domains.updated_at');

        $datatable = DataTables::of($domains);
        $datatable->editColumn('actions', function($query){
            return "<a class='btn btn-primary btn-sm' href='".route('showDomain', $query->id)."'>Domain Links</a>";
        })
        ->filterColumn('link_count', function($query, $keyword){
            if ($keyword)
            {
                $query->having('link_count', 'like', '%' . $keyword . '%');
            }
        })
        ->rawColumns(['actions']);

        return $datatable->toJson();
    }

    public function add() 
    {
        return view('add');
    }

    public function show($domain) 
    {
        $domain = Domain::find($domain);
        $dataUrl = route('fetchLinks', $domain->id);
        return view('show', compact('dataUrl', 'domain'));
    }

    public function fetchLinks($domain, Request $request)
    {
        $links = Link::where('domain_id', $domain);

        $datatable = DataTables::of($links);
        return $datatable->toJson();
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
