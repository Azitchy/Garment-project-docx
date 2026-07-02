@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $pageMeta['title'] }}</h1>
            <p>{{ $pageMeta['subtitle'] }}</p>
        </div>
        <div class="updated">Finance / {{ strtoupper(str_replace('-', ' ', $pageKey)) }}</div>
    </div>

    <section class="content-grid" style="margin-bottom:18px;">
        <div class="card">
            <div class="section-title">
                <h2>{{ $pageMeta['title'] }} Overview</h2>
                <a class="pill" href="{{ route('dashboard.section', 'finance') }}">Back to Finance</a>
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
                <h2>Finance Snapshot</h2>
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
                            <div class="icon-box tone-{{ $item['tone'] ?? 'blue' }}">{{ $item['icon'] ?? 'FN' }}</div>
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
                <h2>Finance Pages</h2>
                <span class="pill">Quick switch</span>
            </div>
            <div class="list">
                <a class="activity" href="{{ route('dashboard.finance.page', 'income-management') }}">
                    <div>
                        <strong>Income Management</strong>
                        <div class="subtle">Sales income, invoices, payments, and balances.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.finance.page', 'expense-management') }}">
                    <div>
                        <strong>Expense Management</strong>
                        <div class="subtle">Daily operating spend and approvals.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.finance.page', 'purchase-payments') }}">
                    <div>
                        <strong>Purchase Payments</strong>
                        <div class="subtle">Supplier invoices and accounts payable updates.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.finance.page', 'cash-bank') }}">
                    <div>
                        <strong>Cash & Bank Management</strong>
                        <div class="subtle">Daily liquidity, deposits, withdrawals, and balances.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.finance.page', 'receivables') }}">
                    <div>
                        <strong>Accounts Receivable</strong>
                        <div class="subtle">Outstanding invoices, due dates, and overdue tracking.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.finance.page', 'payables') }}">
                    <div>
                        <strong>Accounts Payable</strong>
                        <div class="subtle">Purchase bills, due dates, and supplier obligations.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.finance.page', 'reconciliation') }}">
                    <div>
                        <strong>Bank Reconciliation</strong>
                        <div class="subtle">Compare company cash book and bank statement.</div>
                    </div>
                </a>
                <a class="activity" href="{{ route('dashboard.finance.page', 'reports') }}">
                    <div>
                        <strong>Financial Reporting</strong>
                        <div class="subtle">Profit & Loss, Balance Sheet, Cash Flow, and more.</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <h2>Organization Value</h2>
                <span class="pill">Audit-ready</span>
            </div>
            <div class="list">
                <div class="activity">
                    <div>
                        <strong>Better control of daily finance</strong>
                        <div class="subtle">Income and expenses are visible as they happen.</div>
                    </div>
                </div>
                <div class="activity">
                    <div>
                        <strong>Cleaner cash planning</strong>
                        <div class="subtle">Receivables, payables, and bank data stay in sync.</div>
                    </div>
                </div>
                <div class="activity">
                    <div>
                        <strong>Monthly reporting support</strong>
                        <div class="subtle">Leadership can review core statements without manual consolidation.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
