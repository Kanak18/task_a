<?php

namespace App\Jobs;

use App\Models\Upload;
use App\Models\Product;
use App\Models\Image;
use Illuminate\Contracts\Queue\ShouldQueue;
use Intervention\Image\ImageManager;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class ProcessImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Upload $upload,
        public Product $product
    ) {}

    public function handle()
    {
        $manager = ImageManager::gd();
        $img = $manager->read($this->upload->temp_path);

        foreach ([256,512,1024] as $size) {
            $clone = clone $img;
            $clone->scaleDown($size);
            $paths[$size] = "images/{$size}_{$this->upload->uuid}.jpg";
            $dir = dirname(storage_path("app/public/".$paths[$size]));
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $clone->save(storage_path("app/public/".$paths[$size]));
        }

        Image::firstOrCreate(
            [
                'upload_id'=>$this->upload->id,
                'product_id'=>$this->product->id
            ],
            [
                'path_256'=>$paths[256],
                'path_512'=>$paths[512],
                'path_1024'=>$paths[1024]
            ]
        );
    }
}
