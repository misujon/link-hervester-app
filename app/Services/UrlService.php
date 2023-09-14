<?php

namespace App\Services;

use App\Models\Domain;
use App\Models\Link;
use Illuminate\Support\Facades\Log;


class UrlService
{
    public function getDomain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];

        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) 
        {
            return $regs['domain'];
        }
        return false;
    }

    public function saveUrls(array $urls)
    {
        try
        {
            $domainData = [];
            $uniqueDomains = [];
            foreach ($urls as $url)
            {
                try
                {
                    $host = $this->getDomain($url);
                    $domainData[] = ['domain_name' => $host];
                    $uniqueDomains[] = $host;
                }
                catch (\Exception $e)
                {
                    Log::error("Error in UrlService -> saveUrls: " . $e->getMessage());
                    continue;
                }
            }
    
            $insertDomain = Domain::upsert($domainData, ['domain_name'], ['domain_name']);
            if (!$insertDomain) return false;
            
            $getDomains = Domain::whereIn('domain_name', array_unique($uniqueDomains))->get();
            $linkData = [];
            foreach ($getDomains as $dom)
            {
                try
                {
                    $checker = $this;
                    collect($urls)->map(function($data) use (&$linkData, $dom, $checker){
                        $parsedUrl = $checker->getDomain($data);
                        if (isset($parsedUrl) && $parsedUrl == $dom->domain_name)
                        {
                            $linkData[] = [
                                'domain_id' => $dom->id,
                                'url' => $data
                            ];
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    });
                }
                catch(\Exception $e)
                {
                    Log::error("Error in url store data formation.", [$e]);
                    continue;
                }
            }
    
            $insertLinks = Link::upsert($linkData, ['url'], ['url']);
            if (!$insertLinks) return false;
            
            return true;
        }
        catch (\Exception $e)
        {
            Log::error("Error in saveUrls", [$e]);
            return false;
        }
    }
}