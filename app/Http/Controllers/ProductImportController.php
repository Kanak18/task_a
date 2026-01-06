<?php

namespace App\Http\Controllers;

use App\Jobs\ImportProductsJob;
use App\Models\Product;
use App\Models\ProductImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImportController extends Controller
{

     public function store(Request $request)
    {
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        // Save file
        $path = $request->file('csv')->store('imports');

        // Read CSV headers
        $handle = fopen(Storage::path($path), 'r');
        $header = fgetcsv($handle);
        fclose($handle);

        if (!$header) {
            return back()->withErrors(['csv' => 'CSV file is empty']);
        }

        $header = array_map('trim', $header);
        $required = ['sku', 'name'];
        $allowed = (new Product())->getFillable();

        $missing = array_diff($required, $header);
        if ($missing) {
            return back()->withErrors(['csv' => 'Missing required column(s): '.implode(', ', $missing)]);
        }

        $invalidColumns = array_diff($header, $allowed);
        if ($invalidColumns) {
            return back()->withErrors(['csv' => 'Invalid column(s): '.implode(', ', $invalidColumns)]);
        }

        // Create import record
        $totalRows = count(file(Storage::path($path))) - 1;
        $productImport = ProductImport::create([
            'filename' => $request->file('csv')->getClientOriginalName(),
            'expected_total' => $totalRows
        ]);

        // Dispatch job
        ImportProductsJob::dispatch($path, $productImport->id, $totalRows);

        return back()->with('success', 'CSV validated and import started.')
                     ->with('import_id', $productImport->id);
    }

    public function progress(ProductImport $import)
    {
        return response()->json($import);
    }
}