@extends('layouts.admin')

@section('content')
    <section class="section">
        <div style="display:flex;justify-content:space-between;gap:16px;align-items:center;flex-wrap:wrap;">
            <div>
                <div class="eyebrow">Garment Catalog</div>
                <h1 style="font-size:2rem;margin-bottom:8px;">Manage inventory</h1>
                <p class="meta">Add, update, or archive garments from the backend.</p>
            </div>
            <a class="button primary" href="{{ route('admin.garments.create') }}">Create Garment</a>
        </div>

        <table class="table" style="margin-top:20px;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($garments as $garment)
                    <tr>
                        <td>{{ $garment->name }}</td>
                        <td>{{ $garment->category }}</td>
                        <td>Rs. {{ number_format((float) $garment->price, 2) }}</td>
                        <td>{{ $garment->stock }}</td>
                        <td>{{ $garment->is_active ? 'Active' : 'Hidden' }}</td>
                        <td>
                            <a class="button secondary" href="{{ route('admin.garments.edit', $garment) }}">Edit</a>
                            <form action="{{ route('admin.garments.destroy', $garment) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="button secondary" type="submit" onclick="return confirm('Delete this garment?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No garments available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:18px;">
            {{ $garments->links() }}
        </div>
    </section>
@endsection
