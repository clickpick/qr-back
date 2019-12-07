<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadImageRequest;
use App\Jobs\DeleteTempImage;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadImageController extends Controller
{
    public function temp(UploadImageRequest $request)
    {
        $user = Auth::user();

        $image = Image::make($request->base64);

        $fileName = $user->id . random_int(1, 1000) . '.jpg';
        $path = "temp{$fileName}";

        Storage::disk('public')->put($path, $image->stream('jpg', 100));

        DeleteTempImage::dispatch($path)->delay(Carbon::now()->addHour());

        return new Resource([
            'url' => Storage::disk('public')->url($path)
        ]);
    }
}
