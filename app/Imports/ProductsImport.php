<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    public array $summary;

    public function __construct(&$summary)
    {
        $this->summary = &$summary;
    }

    public function collection(Collection $rows)
    {
        $seen = [];

        foreach ($rows as $row) {
            $this->summary['total']++;

            if (!isset($row['sku'],$row['name'],$row['price'])) {
                $this->summary['invalid']++;
                continue;
            }

            if (isset($seen[$row['sku']])) {
                $this->summary['duplicates']++;
                continue;
            }

            $seen[$row['sku']] = true;

            $existing = Product::where('sku',$row['sku'])->exists();

            Product::updateOrCreate(
                ['sku'=>$row['sku']],
                [
                    'name'=>$row['name'],
                    'description'=>$row['description'] ?? null,
                    'price'=>$row['price']
                ]
            );

            $existing
                ? $this->summary['updated']++
                : $this->summary['imported']++;
        }
    }
}