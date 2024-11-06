<?php

namespace App\Jobs;

use App\Models\ImportProduct;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadFoodFileJob implements ShouldQueue
{
    use Batchable, Queueable;

    public int $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ImportProduct $importProduct
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $response = Http::get('https://static.openfoodfacts.org/data/delta/' . $this->importProduct->filename);
        $response = Http::get('https://challenges.coode.sh/food/data/json/' . $this->importProduct->filename);

        $contents = $response->body();
        Storage::disk('local')->put('import/' . $this->importProduct->filename, $contents);

        $this->importProduct->update([
            'status' => 'downloaded',
        ]);
    }
}
