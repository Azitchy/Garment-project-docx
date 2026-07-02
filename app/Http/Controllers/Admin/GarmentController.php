<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GarmentController extends Controller
{
    public function index(): View
    {
        return view('admin.garments.index', [
            'garments' => Garment::query()->latest()->paginate(10)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.garments.create', [
            'garment' => new Garment(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateGarment($request);

        Garment::create($data);

        return redirect()
            ->route('admin.garments.index')
            ->with('status', 'Garment created successfully.');
    }

    public function edit(Garment $garment): View
    {
        return view('admin.garments.edit', compact('garment'));
    }

    public function update(Request $request, Garment $garment): RedirectResponse
    {
        $data = $this->validateGarment($request);

        $garment->update($data);

        return redirect()
            ->route('admin.garments.index')
            ->with('status', 'Garment updated successfully.');
    }

    public function destroy(Garment $garment): RedirectResponse
    {
        $garment->delete();

        return redirect()
            ->route('admin.garments.index')
            ->with('status', 'Garment deleted successfully.');
    }

    private function validateGarment(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:120'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
