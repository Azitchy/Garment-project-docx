<?php

use App\Models\Garment;
use Illuminate\Support\Facades\Route;

Route::get('/garments', function () {
    return Garment::query()
        ->where('is_active', true)
        ->latest()
        ->get();
});
