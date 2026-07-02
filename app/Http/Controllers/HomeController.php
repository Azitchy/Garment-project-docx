<?php

namespace App\Http\Controllers;

use App\Models\Garment;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $garments = Garment::query()
            ->where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        return view('home', [
            'garments' => $garments,
            'featuredCount' => Garment::query()->where('is_active', true)->count(),
        ]);
    }
}
