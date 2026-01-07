<?php

namespace Tests\Unit;

use App\Imports\ProductsImport;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProductsImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_upsert_logic_creates_new_product()
    {
        $summary = ['total' => 0, 'invalid' => 0, 'duplicates' => 0, 'imported' => 0, 'updated' => 0];

        $import = new ProductsImport($summary);

        $rows = new Collection([
            ['sku' => 'TEST123', 'name' => 'Test Product', 'price' => 10.99]
        ]);

        $import->collection($rows);

        $this->assertEquals(1, $summary['total']);
        $this->assertEquals(0, $summary['invalid']);
        $this->assertEquals(0, $summary['duplicates']);
        $this->assertEquals(1, $summary['imported']);
        $this->assertEquals(0, $summary['updated']);

        $product = Product::where('sku', 'TEST123')->first();
        $this->assertNotNull($product);
        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals(10.99, $product->price);
    }

    public function test_upsert_logic_updates_existing_product()
    {
        // Create existing product
        Product::create([
            'sku' => 'TEST123',
            'name' => 'Old Name',
            'price' => 5.00
        ]);

        $summary = ['total' => 0, 'invalid' => 0, 'duplicates' => 0, 'imported' => 0, 'updated' => 0];

        $import = new ProductsImport($summary);

        $rows = new Collection([
            ['sku' => 'TEST123', 'name' => 'Updated Product', 'price' => 15.99]
        ]);

        $import->collection($rows);

        $this->assertEquals(1, $summary['total']);
        $this->assertEquals(0, $summary['invalid']);
        $this->assertEquals(0, $summary['duplicates']);
        $this->assertEquals(0, $summary['imported']);
        $this->assertEquals(1, $summary['updated']);

        $product = Product::where('sku', 'TEST123')->first();
        $this->assertNotNull($product);
        $this->assertEquals('Updated Product', $product->name);
        $this->assertEquals(15.99, $product->price);
    }

    public function test_handles_invalid_rows()
    {
        $summary = ['total' => 0, 'invalid' => 0, 'duplicates' => 0, 'imported' => 0, 'updated' => 0];

        $import = new ProductsImport($summary);

        $rows = new Collection([
            ['sku' => 'TEST123', 'name' => 'Test Product'], // missing price
            ['name' => 'Another', 'price' => 10.00] // missing sku
        ]);

        $import->collection($rows);

        $this->assertEquals(2, $summary['total']);
        $this->assertEquals(2, $summary['invalid']);
        $this->assertEquals(0, $summary['duplicates']);
        $this->assertEquals(0, $summary['imported']);
        $this->assertEquals(0, $summary['updated']);
    }

    public function test_handles_duplicates_in_same_import()
    {
        $summary = ['total' => 0, 'invalid' => 0, 'duplicates' => 0, 'imported' => 0, 'updated' => 0];

        $import = new ProductsImport($summary);

        $rows = new Collection([
            ['sku' => 'TEST123', 'name' => 'Test Product', 'price' => 10.99],
            ['sku' => 'TEST123', 'name' => 'Duplicate', 'price' => 20.00]
        ]);

        $import->collection($rows);

        $this->assertEquals(2, $summary['total']);
        $this->assertEquals(0, $summary['invalid']);
        $this->assertEquals(1, $summary['duplicates']);
        $this->assertEquals(1, $summary['imported']);
        $this->assertEquals(0, $summary['updated']);

        // Only one product should be created
        $this->assertEquals(1, Product::where('sku', 'TEST123')->count());
    }
}