<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Image;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index',[
            'products'=>Product::paginate(20),
            'summary'=>cache()->get('import_summary')
        ]);
    }

    public function images(Product $product)
    {
        return view('products.images',compact('product'));
    }

    public function primary(Image $image)
    {
        $image->product->images()->update(['is_primary'=>false]);
        $image->update(['is_primary'=>true]);
        $image->product->update(['primary_image_id'=>$image->id]);

        return back();
    }

    public function setPrimary(Request $request, Product $product)
    {
        $request->validate([
            'primary' => 'required|string'
        ]);

        [$imageId, $size] = explode('-', $request->primary);

        $image = Image::findOrFail($imageId);

        // Ensure the image belongs to the product
        if ($image->product_id !== $product->id) {
            abort(403);
        }

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
        $product->update([
            'primary_image_id' => $image->id,
            'primary_size' => $size
        ]);

        return back()->with('success', 'Primary image updated successfully.');
    }
    public function downloadSample()
    {
        $headers = ['SKU', 'Name', 'Description', 'Price'];
        $sampleData = [
            ['sku' => 'SAMPLE-001', 'name' => 'Sample Product 1', 'description' => 'This is a sample product description', 'price' => '29.99'],
            ['sku' => 'SAMPLE-002', 'name' => 'Sample Product 2', 'description' => 'Another sample product', 'price' => '49.99'],
        ];

        $filename = 'sample_products.csv';
        $handle = fopen('php://temp', 'w+');

        // Write headers
        fputcsv($handle, $headers);

        // Write sample data
        foreach ($sampleData as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
