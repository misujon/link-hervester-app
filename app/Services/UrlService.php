<?php

namespace App\Services;

use App\Models\Domain;
use App\Models\Link;
use Illuminate\Support\Facades\Log;


class UrlService
{
    // Method that pull the domain name only from the url.
    public function getDomain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
        return $domain;
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
                    // Formatting data for bulk insert into Domains table.
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
            
            // Upserting the bulk domains, which inserts the domains and if there's existing one, then updates it.
            Domain::upsert($domainData, ['domain_name'], ['domain_name']);
            $getDomains = Domain::whereIn('domain_name', array_unique($uniqueDomains))->get();

            Log::info("Get domains upsert: ".count($domainData));
            $linkData = [];
            foreach ($getDomains as $dom)
            {
                try
                {
                    $getChunkedUrls = [];
                    foreach ($urls as $data) 
                    {
                        // Formatting urls with the related domain id.
                        $parsedUrl = $this->getDomain($data);
                        if (isset($parsedUrl) && $parsedUrl == $dom->domain_name) 
                        {
                            $getChunkedUrls[] = [
                                'domain_id' => $dom->id,
                                'url' => $data
                            ];
                        }
                    }

                    // Keeping all the links data here to insert bulk.
                    $linkData = array_merge($getChunkedUrls, $linkData);
                }
                catch(\Exception $e)
                {
                    Log::error("Error in url store data formation.", [$e]);
                    continue;
                }
            }
    
            // Bulk inserting the links with the formatted data.
            $insertLinks = Link::upsert($linkData, ['url'], ['domain_id']);
            Log::info("Total upsert: ".$insertLinks);
            
            return true;
        }
        catch (\Exception $e)
        {
            Log::error("Error in saveUrls", [$e]);
            return false;
        }
    }
}