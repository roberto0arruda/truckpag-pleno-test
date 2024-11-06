<?php

namespace App\Jobs;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportFoodFileJob implements ShouldQueue
{
    use Batchable, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $importProduct
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            // Determine if the batch has been cancelled...

            return;
        }

        // Import a portion of the file...
        $code = preg_replace('/\D+/', '', $this->importProduct['code']);

        $persistentProduct = Product::firstOrNew(['code' => $code]);

        if (!$persistentProduct->exists) {
            $persistentProduct->status = 'draft';
            $persistentProduct->imported_t = Carbon::now()->toIso8601String();
        }

        $persistentProduct->url = $this->importProduct['url'];
        $persistentProduct->creator = $this->importProduct['creator'];
        $persistentProduct->created_t = $this->importProduct['created_t'];
        $persistentProduct->last_modified_t = $this->importProduct['last_modified_t'];
        $persistentProduct->product_name = $this->importProduct['product_name'];
        $persistentProduct->quantity = $this->importProduct['quantity'];
        $persistentProduct->brands = $this->importProduct['brands'];
        $persistentProduct->categories = $this->importProduct['categories'];
        $persistentProduct->labels = $this->importProduct['labels'];
        $persistentProduct->cities = $this->importProduct['cities'];
        $persistentProduct->purchase_places = $this->importProduct['purchase_places'];
        $persistentProduct->stores = $this->importProduct['stores'];
        $persistentProduct->ingredients_text = $this->importProduct['ingredients_text'];
        $persistentProduct->traces = $this->importProduct['traces'];
        $persistentProduct->serving_size = $this->importProduct['serving_size'];
        $persistentProduct->serving_quantity = $this->importProduct['serving_quantity'];
        $persistentProduct->nutriscore_score = $this->importProduct['nutriscore_score'];
        $persistentProduct->nutriscore_grade = $this->importProduct['nutriscore_grade'];
        $persistentProduct->main_category = $this->importProduct['main_category'];
        $persistentProduct->image_url = $this->importProduct['image_url'];

        $persistentProduct->save();
    }
}
