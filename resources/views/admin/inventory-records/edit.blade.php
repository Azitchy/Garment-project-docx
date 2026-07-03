@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>Edit Inventory Record</h1>
            <p>Update the selected inventory entry and keep the module data consistent.</p>
        </div>
        <div class="updated">
            <a class="pill" href="{{ route('admin.inventory-records.index', ['type' => $selectedType]) }}">Back to Inventory Admin</a>
        </div>
    </div>

    <section class="card">
        <form action="{{ route('admin.inventory-records.update', $record) }}" method="POST">
            @include('admin.inventory-records._form')
        </form>
    </section>
@endsection
