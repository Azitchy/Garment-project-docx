@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $pageMeta['title'] }}</h1>
            <p>{{ $pageMeta['subtitle'] }}</p>
        </div>
        <div class="updated">HR & Payroll / {{ strtoupper(str_replace('-', ' ', $pageKey)) }}</div>
    </div>

    <section class="content-grid" style="margin-bottom:18px;">
        <div class="card">
            <div class="section-title">
                <h2>{{ $pageMeta['title'] }} Overview</h2>
                <a class="pill" href="{{ route('dashboard.section', 'hr-payroll') }}">Back to HR & Payroll</a>
            </div>
            <p class="subtle" style="margin-top:0;">{{ $pageMeta['banner'] }}</p>

            <div class="list">
                @foreach ($pageMeta['focus'] as $item)
                    <div class="activity">
                        <div>
                            <strong>{{ $item }}</strong>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2>HR Snapshot</h2>
                <span class="pill">Current state</span>
            </div>
            <div class="grid-cards" style="grid-template-columns:repeat(2,minmax(0,1fr));margin-bottom:0;">
                @foreach ($highlights as $item)
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
            </div>
        </div>
    </section>

    <section class="card" style="margin-bottom:18px;">
        <div class="section-title">
            <h2>Connected Actions</h2>
            <span class="pill">Navigate module</span>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            @foreach ($pageMeta['actions'] as $label => $url)
                <a class="pill" href="{{ $url }}">{{ $label }}</a>
            @endforeach
        </div>
    </section>

    <section class="content-grid">
        <div class="card">
            <div class="section-title">
                <h2>HR & Payroll Pages</h2>
                <span class="pill">Quick switch</span>
            </div>
            <div class="list">
                <a class="activity" href="{{ route('dashboard.hr-payroll.page', 'employee-management') }}">
                    <div>
                        <strong>Employee Management</strong>
                        <div class="subtle">Employee profiles, departments, and job roles.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.hr-payroll.page', 'attendance') }}">
                    <div>
                        <strong>Attendance</strong>
                        <div class="subtle">Daily attendance, shifts, late arrival, and overtime.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.hr-payroll.page', 'payroll') }}">
                    <div>
                        <strong>Payroll</strong>
                        <div class="subtle">Salary, deductions, net pay, and payslips.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.hr-payroll.page', 'leave-management') }}">
                    <div>
                        <strong>Leave Management</strong>
                        <div class="subtle">Requests, balances, approvals, and absences.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.hr-payroll.page', 'performance') }}">
                    <div>
                        <strong>Performance</strong>
                        <div class="subtle">Reviews, goals, appraisals, and development.</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2>Organization Value</h2>
                <span class="pill">Operations-ready</span>
            </div>
            <div class="list">
                <div class="activity">
                    <div>
                        <strong>Single source of truth</strong>
                        <div class="subtle">Employee and payroll records stay aligned for the whole organization.</div>
                    </div>
                </div>
                <div class="activity">
                    <div>
                        <strong>Better workforce control</strong>
                        <div class="subtle">Attendance and leave management help teams plan shifts and coverage.</div>
                    </div>
                </div>
                <div class="activity">
                    <div>
                        <strong>Payroll accuracy</strong>
                        <div class="subtle">Structured wage, deduction, and approval flows reduce manual mistakes.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
