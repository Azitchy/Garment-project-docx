@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $pageMeta['title'] }}</h1>
            <p>{{ $pageMeta['subtitle'] }}</p>
        </div>
        <div class="updated">HR & Payroll / {{ strtoupper(str_replace('-', ' ', $pageKey)) }}</div>
    </div>

    <section class="card" style="margin-bottom:18px;">
        <div class="section-title">
            <div>
                <h2>{{ $pageMeta['title'] }}</h2>
                <p class="subtle" style="margin:6px 0 0;">{{ $pageMeta['banner'] }}</p>
            </div>
            <span class="pill">CRUD ready</span>
        </div>

        <p class="subtle" style="margin-top:0;">{{ $hrPayrollCalculation }}</p>

        <section class="grid-cards" style="grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:0;">
            @foreach ($hrPayrollMetrics as $item)
                <div class="card" style="box-shadow:none;">
                    <div class="metric">
                        <div>
                            <h3>{{ $item['label'] }}</h3>
                            <strong>{{ $item['value'] }}</strong>
                        </div>
                        <div class="icon-box tone-{{ $item['tone'] ?? 'blue' }}">{{ $item['icon'] ?? 'HR' }}</div>
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
            <a class="pill" href="{{ route('dashboard.section.create', ['section' => 'hr-payroll', 'type' => $pageKey]) }}">Create {{ $pageMeta['title'] }} Record</a>
            <a class="pill" href="{{ route('dashboard.section', 'hr-payroll') }}">HR Overview</a>
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
            <a class="pill" href="{{ route('dashboard.section.create', ['section' => 'hr-payroll', 'type' => $pageKey]) }}">Add {{ $pageMeta['title'] }} Record</a>
        </div>

        <div style="overflow:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ $fieldLabels['title'] ?? 'Title' }}</th>
                        <th>{{ $pageKey === 'attendance' ? 'Present / Absent' : ($fieldLabels['meta'] ?? 'Meta') }}</th>
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
                                <div class="subtle">{{ $record->employee_id ?: $record->record_type ?: '-' }}</div>
                            </td>
                            <td>
                                @if ($pageKey === 'attendance')
                                    <div><strong>Present:</strong></div>
                                    <div class="subtle" style="white-space:pre-wrap;">{{ $record->present_employees ?: '-' }}</div>
                                    <div style="margin-top:8px;"><strong>Absent:</strong></div>
                                    <div class="subtle" style="white-space:pre-wrap;">{{ $record->absent_employees ?: '-' }}</div>
                                @else
                                    {{ $record->meta ?: '-' }}
                                @endif
                            </td>
                            <td>{{ $record->status ?: 'Active' }}</td>
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
                            <td colspan="6">No records found for this HR & Payroll submenu.</td>
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
