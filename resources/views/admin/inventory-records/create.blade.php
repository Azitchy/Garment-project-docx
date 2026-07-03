@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>Create Inventory Record</h1>
            <p>Add a product, stock movement, supplier, customer, transfer, adjustment, or alert record.</p>
        </div>
        <div class="updated">
            <a class="pill" href="{{ route('admin.inventory-records.index') }}">Back to Inventory Admin</a>
        </div>
    </div>

    <section class="card">
        <form action="{{ route('admin.inventory-records.store') }}" method="POST">
            @include('admin.inventory-records._form')
        </form>
    </section>
@endsection
