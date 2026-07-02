@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $formMode === 'create' ? 'Create ' . $sectionMeta['title'] : 'Edit ' . $sectionMeta['title'] }}</h1>
            <p>{{ $sectionMeta['subtitle'] }}</p>
        </div>
        <div class="updated">
            <a class="pill" href="{{ route('dashboard.section', $sectionKey) }}">Back to list</a>
        </div>
    </div>

    <section class="card">
        <form action="{{ $formMode === 'create' ? route('dashboard.section.store', $sectionKey) : route('dashboard.section.update', [$sectionKey, $record]) }}" method="POST">
            @csrf
            @if ($formMode === 'edit')
                @method('PUT')
            @endif

            <div class="form">
                <div class="field">
                    <label for="title">Title</label>
                    <input id="title" name="title" value="{{ old('title', $record->title) }}" required>
                    @error('title')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="meta">Meta</label>
                    <input id="meta" name="meta" value="{{ old('meta', $record->meta) }}">
                    @error('meta')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="status">Status</label>
                    <input id="status" name="status" value="{{ old('status', $record->status) }}">
                    @error('status')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="value">Value</label>
                    <input id="value" name="value" value="{{ old('value', $record->value) }}">
                    @error('value')<div class="error">{{ $message }}</div>@enderror
                </div>

                @if (in_array($sectionKey, ['master-data', 'orders', 'finance'], true))
                    <div class="card" style="grid-column:1 / -1;box-shadow:none;background:#f8fafc;">
                        <div class="section-title">
                            <h2>Nepal Business Details</h2>
                            <span class="pill">PAN/VAT and address</span>
                        </div>

                        <div class="content-grid">
                            <div class="field">
                                <label for="pan_vat">PAN / VAT</label>
                                <input id="pan_vat" name="pan_vat" value="{{ old('pan_vat', $record->pan_vat) }}" placeholder="PAN or VAT number">
                                @error('pan_vat')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="province">Province</label>
                                <input id="province" name="province" value="{{ old('province', $record->province) }}" placeholder="Province">
                                @error('province')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="district">District</label>
                                <input id="district" name="district" value="{{ old('district', $record->district) }}" placeholder="District">
                                @error('district')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="municipality">Municipality</label>
                                <input id="municipality" name="municipality" value="{{ old('municipality', $record->municipality) }}" placeholder="Municipality / Rural Municipality">
                                @error('municipality')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="ward">Ward</label>
                                <input id="ward" name="ward" value="{{ old('ward', $record->ward) }}" placeholder="Ward number">
                                @error('ward')<div class="error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                @endif

                @if (in_array($sectionKey, ['orders', 'finance'], true))
                    <div class="card" style="grid-column:1 / -1;box-shadow:none;background:#f8fafc;">
                        <div class="section-title">
                            <h2>Transaction Details</h2>
                            <span class="pill">Order / invoice</span>
                        </div>

                        <div class="content-grid">
                            <div class="field">
                                <label for="order_type">Order Type</label>
                                <input id="order_type" name="order_type" value="{{ old('order_type', $record->order_type) }}" placeholder="Domestic, Export, Sample">
                                @error('order_type')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="invoice_no">Invoice No.</label>
                                <input id="invoice_no" name="invoice_no" value="{{ old('invoice_no', $record->invoice_no) }}" placeholder="Tax invoice number">
                                @error('invoice_no')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="due_date">Due Date</label>
                                <input id="due_date" type="date" name="due_date" value="{{ old('due_date', optional($record->due_date)->format('Y-m-d')) }}">
                                @error('due_date')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="currency">Currency</label>
                                <input id="currency" name="currency" value="{{ old('currency', $record->currency ?? 'NPR') }}" placeholder="NPR">
                                @error('currency')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="payment_mode">Payment Mode</label>
                                <input id="payment_mode" name="payment_mode" value="{{ old('payment_mode', $record->payment_mode) }}" placeholder="Cash, Bank Transfer, Digital Payment">
                                @error('payment_mode')<div class="error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                @endif

                @if ($sectionKey === 'hr-payroll')
                    <div class="card" style="grid-column:1 / -1;box-shadow:none;background:#f8fafc;">
                        <div class="section-title">
                            <h2>HR Compliance Details</h2>
                            <span class="pill">Employee record</span>
                        </div>

                        <div class="content-grid">
                            <div class="field">
                                <label for="employee_id">Employee ID</label>
                                <input id="employee_id" name="employee_id" value="{{ old('employee_id', $record->employee_id) }}" placeholder="Employee ID">
                                @error('employee_id')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="employment_type">Employment Type</label>
                                <input id="employment_type" name="employment_type" value="{{ old('employment_type', $record->employment_type) }}" placeholder="Permanent, Contract, Part-time">
                                @error('employment_type')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="ssf_no">SSF / Social Security No.</label>
                                <input id="ssf_no" name="ssf_no" value="{{ old('ssf_no', $record->ssf_no) }}" placeholder="SSF number">
                                @error('ssf_no')<div class="error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                @endif

                <div class="field">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="5">{{ old('notes', $record->notes) }}</textarea>
                    @error('notes')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $record->is_active ?? true))>
                        Active
                    </label>
                </div>

                <div class="form-actions">
                    <button class="button primary" type="submit">{{ $formMode === 'create' ? 'Create Record' : 'Update Record' }}</button>
                    <a class="button secondary" href="{{ route('dashboard.section', $sectionKey) }}">Cancel</a>
                </div>
            </div>
        </form>
    </section>
@endsection
