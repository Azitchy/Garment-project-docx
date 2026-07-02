@extends('layouts.app')

@section('content')
    <section class="hero">
        <div>
            <div class="eyebrow">Garment Commerce</div>
            <h1>Manage products, stock, and sales from one Laravel app.</h1>
            <p class="lead">
                This starter gives you a polished customer-facing frontend, an admin backend for garment
                management, and a lightweight JSON API for integrations.
            </p>
            <div class="actions">
                <a class="button primary" href="{{ route('admin.garments.index') }}">Open Backend</a>
                <a class="button secondary" href="#catalog">Browse Catalog</a>
            </div>
        </div>

        <div class="stats">
            <div class="stat">
                <strong>{{ $featuredCount }}</strong>
                <span class="meta">Active garments</span>
            </div>
            <div class="stat">
                <strong>CRUD</strong>
                <span class="meta">Create, edit, and delete garments</span>
            </div>
            <div class="stat">
                <strong>Blade</strong>
                <span class="meta">Server-rendered frontend</span>
            </div>
            <div class="stat">
                <strong>JSON</strong>
                <span class="meta">API endpoint for app integrations</span>
            </div>
        </div>
    </section>

    <section class="section" id="catalog">
        <h2>Featured Garments</h2>
        <p class="meta">These items come from the database and are surfaced on the frontend.</p>

        <div class="grid">
            @forelse ($garments as $garment)
                <article class="item">
                    <div class="tag">{{ $garment->category }}</div>
                    <h3>{{ $garment->name }}</h3>
                    <p class="meta">{{ $garment->description }}</p>
                    <p><strong>Rs. {{ number_format((float) $garment->price, 2) }}</strong></p>
                    <p class="meta">Stock: {{ $garment->stock }}</p>
                </article>
            @empty
                <div class="item">
                    <h3>No garments yet</h3>
                    <p class="meta">Seed the database to see catalog items on the homepage.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
