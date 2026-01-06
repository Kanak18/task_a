<?php
namespace App\Http\Controllers;

use App\Models\Upload;
use App\Models\Product;
use App\Jobs\ProcessImageJob;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function chunk(Request $r)
    {        
        $upload = Upload::firstOrCreate(
            ['uuid'=>$r->uuid],
            ['total_chunks'=>$r->total_chunks]
        );

        $dir = storage_path("app/chunks/{$r->uuid}");
        if (!is_dir($dir)) mkdir($dir,0777,true);

        $r->file('file')->move($dir,"chunk_{$r->chunk_index}");

        $upload->received_chunks = count(glob("$dir/chunk_*"));
        $upload->save();
    }

    public function complete(Request $r, Product $product)
    {
        $upload = Upload::where('uuid',$r->uuid)->firstOrFail();

        if ($upload->received_chunks != $upload->total_chunks) abort(409);

        $final = storage_path("app/uploads/{$r->uuid}");
        $out = fopen($final,'ab');

        for ($i=0;$i<$upload->total_chunks;$i++) {
            fwrite($out,file_get_contents(
                storage_path("app/chunks/{$r->uuid}/chunk_$i")
            ));
        }
        fclose($out);

        if (hash_file('sha256',$final) !== $r->checksum) abort(422);

        $upload->update(['temp_path'=>$final,'checksum'=>$r->checksum]);

        ProcessImageJob::dispatch($upload,$product);
    }

}
