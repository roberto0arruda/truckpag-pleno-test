<?php

namespace App\Console\Commands;

use App\Jobs\DownloadFoodFileJob;
use App\Jobs\ExtractFoodFileJob;
use App\Models\ImportProduct;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class OpenFoodImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:open-food-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start importing products from OpenFoodFacts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $response = Http::get('https://static.openfoodfacts.org/data/delta/index.txt');
        $response = Http::get('https://challenges.coode.sh/food/data/json/index.txt');

        if ($response->status() === Response::HTTP_OK) {
            $files = $response->getBody()->getContents();

            foreach (array_filter(explode("\n", $files)) as $filename) {
                $import = ImportProduct::create([
                    'filename' => $filename,
                ]);

                $batch = Bus::batch([
                    new DownloadFoodFileJob($import),
                    new ExtractFoodFileJob($import),
                ])->progress(function (Batch $batch) {
                    // A single job has completed successfully...
                })->then(function (Batch $batch) use ($import) {
                    // All jobs completed successfully...
                    $import->update([
                        'status' => 'imported'
                    ]);
                })->catch(function (Batch $batch, \Throwable $e) {
                    // First batch job failure detected...
                })->finally(function (Batch $batch) use ($filename) {
                    // The batch has finished executing...
                    Storage::disk('local')->delete('import/' . basename($filename, '.gz'));
                })->name("Import {$filename}")->dispatch();
            }
        }
    }
}
