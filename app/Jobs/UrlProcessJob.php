<?php

namespace App\Jobs;

use App\Services\UrlService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UrlProcessJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $urls; 
    private $urlService;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->urls = $data;
        $this->urlService = new UrlService();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->urlService->saveUrls($this->urls);
        return; 
    }
}
