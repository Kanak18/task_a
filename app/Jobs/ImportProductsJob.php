<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ProductImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $csvPath;
    protected $importId;
    protected $totalRows;

    public function __construct($csvPath, $importId,$totalRows = null)
    {
        $this->csvPath = $csvPath;
        $this->importId = $importId;
        $this->totalRows = $totalRows;  
        Log::info("Job Created - importId: {$importId}");
    }

    public function handle(): void
    {
        $product = new Product();
        $fillable = $product->getFillable();

        $handle = fopen(Storage::path($this->csvPath), 'r');
        $header = fgetcsv($handle);

        $total = 0;
        $inserted = 0;
        $updated = 0;
        $invalid = 0;
        $duplicates = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $total++;
            $data = array_combine($header, $row);

            if (!$data || empty($data['sku']) || empty($data['name'])) {
                $invalid++;
                ProductImport::where('id', $this->importId)->update(['total' => $total, 'invalid' => $invalid]);
                continue;
            }

            $filtered = array_intersect_key($data, array_flip($fillable));
            $existing = Product::where('sku', $filtered['sku'])->first();

            if ($existing) {
                $duplicates++;
            }

            Product::updateOrCreate(
                ['sku' => $filtered['sku']],
                $filtered
            );

            if ($existing) {
                $updated++;
            } else {
                $inserted++;
            }

            ProductImport::where('id', $this->importId)->update([
                'total' => $total,
                'inserted' => $inserted,
                'updated' => $updated,
                'invalid' => $invalid,
                'duplicates' => $duplicates,                
            ]);
        }

        fclose($handle);
        Log::info("Job Completed - importId: {$this->importId}");
    }
}
