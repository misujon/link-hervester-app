<?php

namespace App\Jobs;

use App\Services\UrlService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UrlProcessJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $urls; 
    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->urls = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
    }
}
