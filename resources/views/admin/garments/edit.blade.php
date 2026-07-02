@extends('layouts.admin')

@section('content')
    <section class="section">
        <div class="eyebrow">Edit Garment</div>
        <h1 style="font-size:2rem;margin-bottom:8px;">Update item</h1>
        <p class="meta">Adjust pricing, stock, or visibility for the catalog.</p>

        <form class="section" action="{{ route('admin.garments.update', $garment) }}" method="POST" style="margin-top:18px;">
            @method('PUT')
            @include('admin.garments._form', ['buttonText' => 'Update Garment'])
        </form>
    </section>
@endsection
