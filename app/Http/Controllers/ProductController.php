<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 15);

        return Product::paginate($limit);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): Product
    {
        return $product;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): \Illuminate\Http\Response
    {
        $product->status = 'trash';

        $product->save();

        return response()->noContent();
    }

    public function status(): \Illuminate\Http\JsonResponse
    {
        $databaseConnection = $this->checkDatabaseConnection();

        $lastCronRun = Cache::get('last_cron_run', 'cron do not executed');

        $serverStartTime = Cache::get('server_start_time', now());
        $uptime = Carbon::parse($serverStartTime)->diffForHumans();

        $memoryUsage = round(memory_get_usage() / 1024 / 1024, 2) . ' MB';

        return response()->json([
            'api_status' => 'online',
            'database_connection' => $databaseConnection,
            'last_cron_run' => $lastCronRun,
            'uptime' => $uptime,
            'memory_usage' => $memoryUsage,
        ]);
    }

    private function checkDatabaseConnection(): ?string
    {
        try {
            DB::connection()->getPdo();
            return 'OK';
        } catch (\Exception $e) {
            return 'Err connection: ' . $e->getMessage();
        }
    }
}
