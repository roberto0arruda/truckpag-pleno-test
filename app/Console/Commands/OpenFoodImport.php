<?php

namespace App\Console\Commands;

use App\Jobs\DownloadFoodFileJob;
use App\Models\ImportProduct;
use Illuminate\Console\Command;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

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
        $response = Http::get('https://challenges.coode.sh/food/data/json/index.txt');

        if ($response->status() === Response::HTTP_OK) {
            $files = $response->getBody()->getContents();

            foreach (array_filter(explode("\n", $files)) as $filename) {
                $import = ImportProduct::create([
                    'filename' => $filename,
                ]);

                DownloadFoodFileJob::dispatch($import)->afterCommit();
            }
        }
    }
}
