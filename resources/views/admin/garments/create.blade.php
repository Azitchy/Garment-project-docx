@extends('layouts.admin')

@section('content')
    <section class="section">
        <div class="eyebrow">Create Garment</div>
        <h1 style="font-size:2rem;margin-bottom:8px;">Add a new item</h1>
        <p class="meta">Use this form to publish new garments into the catalog.</p>

        <form class="section" action="{{ route('admin.garments.store') }}" method="POST" style="margin-top:18px;">
            @include('admin.garments._form', ['buttonText' => 'Save Garment'])
        </form>
    </section>
@endsection
