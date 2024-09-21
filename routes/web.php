<?php

use App\Http\Controllers\ImageController;
use App\Jobs\DemoJob;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('image-upload', [ ImageController::class, 'upload' ])->name('image.upload');
Route::post('image-store', [ ImageController::class, 'store' ])->name('image.upload.post');
Route::get('image-show/{image}', [ ImageController::class, 'show' ]);

Route::get('demo-job', function (){
    DemoJob::dispatch();
    dd('completed');
});
