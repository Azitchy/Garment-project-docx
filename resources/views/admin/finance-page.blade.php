@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $pageMeta['title'] }}</h1>
            <p>{{ $pageMeta['subtitle'] }}</p>
        </div>
        <div class="updated">Finance / {{ strtoupper(str_replace('-', ' ', $pageKey)) }}</div>
    </div>

    <section class="card" style="margin-bottom:18px;">
        <div class="section-title">
            <div>
                <h2>{{ $pageMeta['title'] }}</h2>
                <p class="subtle" style="margin:6px 0 0;">{{ $financeCalculation }}</p>
            </div>
            <span class="pill">CRUD ready</span>
        </div>

        <section class="grid-cards" style="grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:0;">
            @foreach ($financeMetrics as $item)
                <div class="card" style="box-shadow:none;">
                    <div class="metric">
                        <div>
                            <h3>{{ $item['label'] }}</h3>
                            <strong>{{ $item['value'] }}</strong>
                        </div>
                        <div class="icon-box tone-{{ $item['tone'] ?? 'blue' }}">{{ $item['icon'] ?? 'FN' }}</div>
                    </div>
                </div>
            @endforeach
        </section>
    </section>

    <section class="card" style="margin-bottom:18px;">
        <div class="section-title">
            <h2>Connected Actions</h2>
            <span class="pill">Navigation</span>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <a class="pill" href="{{ route('dashboard.section.create', ['section' => 'finance', 'type' => $pageKey]) }}">Create {{ $pageMeta['title'] }} Record</a>
            <a class="pill" href="{{ route('dashboard.section', 'finance') }}">Finance Overview</a>
            @foreach ($pageMeta['actions'] as $label => $url)
                <a class="pill" href="{{ $url }}">{{ $label }}</a>
            @endforeach
        </div>
    </section>

    <section class="card">
        <div class="section-title">
            <div>
                <h2>{{ $pageMeta['title'] }} Records</h2>
                <p class="subtle" style="margin:6px 0 0;">Create, edit, and delete {{ strtolower($pageMeta['title']) }} records from this submenu.</p>
            </div>
            <a class="pill" href="{{ route('dashboard.section.create', ['section' => 'finance', 'type' => $pageKey]) }}">Add {{ $pageMeta['title'] }} Record</a>
        </div>

        <div style="overflow:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ $fieldLabels['title'] ?? 'Title' }}</th>
                        <th>{{ $fieldLabels['meta'] ?? 'Meta' }}</th>
                        <th>{{ $fieldLabels['status'] ?? 'Status' }}</th>
                        <th>{{ $fieldLabels['value'] ?? 'Value' }}</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td>
                                <strong>{{ $record->title }}</strong>
                                <div class="subtle">{{ $record->notes }}</div>
                            </td>
                            <td>
                                {{ $record->meta ?: '-' }}
                                @if (!empty($record->transaction_date))
                                    <div class="subtle">Date: {{ optional($record->transaction_date)->format('M d, Y') }}</div>
                                @endif
                            </td>
                            <td>{{ $record->status ?: '-' }}</td>
                            <td>{{ $record->value ?: '-' }}</td>
                            <td>{{ optional($record->updated_at)->format('M d, Y') ?? '-' }}</td>
                            <td>
                                <a class="button secondary" href="{{ route('dashboard.section.edit', [$sectionKey, $record]) }}">Edit</a>
                                <form action="{{ route('dashboard.section.destroy', [$sectionKey, $record]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button secondary" type="submit" onclick="return confirm('Delete this record?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No records found for this Finance submenu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:18px;">
            {{ $records->links() }}
        </div>
    </section>
@endsection
