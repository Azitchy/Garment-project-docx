@extends('layouts.admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $formMode === 'create' ? 'Create ' . $sectionMeta['title'] : 'Edit ' . $sectionMeta['title'] }}</h1>
            <p>{{ $sectionMeta['subtitle'] }}</p>
        </div>
        <div class="updated">
            <a class="pill" href="{{ $sectionKey === 'hr-payroll' && !empty($recordType) ? route('dashboard.hr-payroll.page', $recordType) : route('dashboard.section', $sectionKey) }}">Back to list</a>
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
                    <label for="title">{{ $fieldLabels['title'] ?? 'Title' }}</label>
                    <input id="title" name="title" value="{{ old('title', $record->title) }}" required>
                    @error('title')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="meta">{{ $fieldLabels['meta'] ?? 'Meta' }}</label>
                    <input
                        id="meta"
                        name="meta"
                        value="{{ old('meta', $record->meta) }}"
                        @if (($recordType ?? $record->record_type ?? '') === 'attendance') placeholder="Shift A, Shift B, Night" @endif
                    >
                    @error('meta')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="status">{{ $fieldLabels['status'] ?? 'Status' }}</label>
                    @php($statusOptions = $fieldOptions['status'] ?? [])
                    @if (! empty($statusOptions))
                        <select id="status" name="status">
                            <option value="">Select status</option>
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $record->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    @else
                        <input id="status" name="status" value="{{ old('status', $record->status) }}">
                    @endif
                    @error('status')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="value">{{ $fieldLabels['value'] ?? 'Value' }}</label>
                    <input id="value" name="value" value="{{ old('value', $record->value) }}">
                    @error('value')<div class="error">{{ $message }}</div>@enderror
                </div>

                @if ($sectionKey === 'hr-payroll')
                    <div class="field">
                        <label for="record_type">Sub-menu</label>
                        <select id="record_type" name="record_type" required>
                            <option value="">Select submenu</option>
                            @foreach ($recordTypeOptions as $key => $label)
                                <option value="{{ $key }}" @selected(old('record_type', $recordType ?? $record->record_type) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('record_type')<div class="error">{{ $message }}</div>@enderror
                    </div>
                @endif

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
                            @php($currentHrType = $recordType ?? $record->record_type ?? '')
                            <h2>{{ $recordTypeOptions[$currentHrType] ?? 'HR Details' }}</h2>
                            <span class="pill">{{ $currentHrType ?: 'HR record' }}</span>
                        </div>

                        <div class="content-grid">
                            @if ($currentHrType === 'attendance')
                                <div class="field">
                                    <label for="transaction_date">{{ $fieldLabels['transaction_date'] ?? 'Attendance Date' }}</label>
                                    <input id="transaction_date" type="date" name="transaction_date" value="{{ old('transaction_date', optional($record->transaction_date)->format('Y-m-d')) }}">
                                    @error('transaction_date')<div class="error">{{ $message }}</div>@enderror
                                </div>
                            @endif

                            <div class="field">
                                <label for="employee_id">{{ $fieldLabels['employee_id'] ?? 'Employee ID / Code' }}</label>
                                <input id="employee_id" name="employee_id" value="{{ old('employee_id', $record->employee_id) }}" placeholder="Employee ID">
                                @error('employee_id')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            <div class="field">
                                <label for="employment_type">{{ $fieldLabels['employment_type'] ?? 'Employment Type' }}</label>
                                <input id="employment_type" name="employment_type" value="{{ old('employment_type', $record->employment_type) }}" placeholder="Permanent, Contract, Part-time">
                                @error('employment_type')<div class="error">{{ $message }}</div>@enderror
                            </div>

                            @if ($currentHrType === 'attendance')
                                <div class="field" style="grid-column:1 / -1;">
                                    <label for="present_employees">{{ $fieldLabels['present_employees'] ?? 'Present Employees' }}</label>
                                    <textarea id="present_employees" name="present_employees" rows="4" placeholder="Enter one employee per line or separate with commas">{{ old('present_employees', $record->present_employees) }}</textarea>
                                    @error('present_employees')<div class="error">{{ $message }}</div>@enderror
                                </div>

                                <div class="field" style="grid-column:1 / -1;">
                                    <label for="absent_employees">{{ $fieldLabels['absent_employees'] ?? 'Absent Employees' }}</label>
                                    <textarea id="absent_employees" name="absent_employees" rows="4" placeholder="Enter one employee per line or separate with commas">{{ old('absent_employees', $record->absent_employees) }}</textarea>
                                    @error('absent_employees')<div class="error">{{ $message }}</div>@enderror
                                </div>
                            @endif

                            <div class="field">
                                <label for="ssf_no">{{ $fieldLabels['ssf_no'] ?? 'SSF / Social Security No.' }}</label>
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
                    <label for="is_active">Record Status</label>
                    @php($isActiveValue = old('is_active', isset($record->is_active) ? (string) (int) $record->is_active : '1'))
                    <select id="is_active" name="is_active">
                        @foreach (($fieldOptions['is_active'] ?? ['1' => 'Active', '0' => 'Inactive']) as $value => $label)
                            <option value="{{ $value }}" @selected((string) $isActiveValue === (string) $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('is_active')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="form-actions">
                    <button class="button primary" type="submit">{{ $formMode === 'create' ? 'Create Record' : 'Update Record' }}</button>
                    <a class="button secondary" href="{{ $sectionKey === 'hr-payroll' && !empty($recordType) ? route('dashboard.hr-payroll.page', $recordType) : route('dashboard.section', $sectionKey) }}">Cancel</a>
                </div>
            </div>
        </form>
    </section>
@endsection
