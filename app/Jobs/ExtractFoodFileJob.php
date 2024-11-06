<?php

namespace App\Jobs;

use App\Models\ImportProduct;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ExtractFoodFileJob implements ShouldQueue
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
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!Storage::disk('local')->exists('import/' . basename($this->importProduct->filename, '.gz'))) {
            // extract
            $compressedContent = Storage::disk('local')->get('import/' . $this->importProduct->filename);

            Storage::disk('local')
                ->put('import/' . basename($this->importProduct->filename, '.gz'), gzdecode($compressedContent));
        }

        $this->importProduct->update([
            'status' => 'importing',
        ]);

        $jsonFilePath = Storage::disk('local')
            ->path('import/' . basename($this->importProduct->filename, '.gz'));

        $handle = fopen($jsonFilePath, 'r');

        if ($handle) {
            $batchSize = 100;
            $count = 0;

            while (($line = fgets($handle)) !== false) {
                $line = trim($line, ",\r\n");

                if (!empty($line)) {
                    $productData = json_decode($line, true, 512, JSON_THROW_ON_ERROR);

                    if (is_array($productData) && json_last_error() === JSON_ERROR_NONE) {

                        $count++;

                        $this->batch()?->add([
                            new ImportFoodFileJob($productData)
                        ]);

                        if ($count >= $batchSize) {
                            break;
                        }
                    }
                }
            }

            fclose($handle);
        }
    }
}
