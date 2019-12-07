<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadImageRequest;
use App\Jobs\DeleteTempImage;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    public function temp(UploadImageRequest $request)
    {
        $path = Storage::disk('public')->putFile('temp', $request->image);

        DeleteTempImage::dispatch($path)->delay(Carbon::now()->addHour());

        return new Resource([
            'url' => Storage::disk('public')->url($path)
        ]);
    }
}
