<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageController extends Controller
{

    public function upload()
    {
        return view('image-upload');
    }

    public function store(Request $request)
    {
//        $request->validate([
//            'title' => 'required',
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//        ]);

        if ($request->hasFile('image')) {

            $path =  $request->file('image')->store('avatar', 's3');

            Image::create([
                'filename' => basename($path),
                'url' => Storage::disk('s3')->url($path)
            ]);


            return redirect()->back()->with([
                'message'=> "Image uploaded successfully",
            ]);
        }
    }

    public function show(Image $image)
    {
        $fileExtension = strtolower(pathinfo($image->url, PATHINFO_EXTENSION));
        $mimeType = match ($fileExtension) {
            'jpeg', 'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            default => 'image/png',
        };
        return Storage::disk('s3')->response('avatar/'. $image->filename,
            null,
            ['Content-Type' => $mimeType]
        );
    }


}
