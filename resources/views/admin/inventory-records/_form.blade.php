@csrf
@if ($formMode === 'edit')
    @method('PUT')
@endif

<div class="form">
    <div class="content-grid">
        <div class="field">
            <label for="record_type">Module Type</label>
            <select id="record_type" name="record_type" required>
                <option value="">Select module</option>
                @foreach ($typeOptions as $key => $label)
                    <option value="{{ $key }}" @selected(old('record_type', $selectedType ?? $record->record_type) === $key)>{{ $label }}</option>
                @endforeach
            </select>
            @error('record_type')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="title">Name</label>
            <input id="title" name="title" value="{{ old('title', $record->title) }}" required placeholder="Product, supplier, customer, or document name">
            @error('title')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="sku">SKU</label>
            <input id="sku" name="sku" value="{{ old('sku', $record->sku) }}" placeholder="SKU code">
            @error('sku')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="barcode">Barcode</label>
            <input id="barcode" name="barcode" value="{{ old('barcode', $record->barcode) }}" placeholder="Barcode / QR code">
            @error('barcode')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="category">Category</label>
            <input id="category" name="category" value="{{ old('category', $record->category) }}" placeholder="Category">
            @error('category')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="unit">Unit</label>
            <input id="unit" name="unit" value="{{ old('unit', $record->unit) }}" placeholder="piece, kg, box, etc.">
            @error('unit')<div class="error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="content-grid">
        <div class="field">
            <label for="supplier">Supplier</label>
            <input id="supplier" name="supplier" value="{{ old('supplier', $record->supplier) }}" placeholder="Supplier name">
            @error('supplier')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="customer">Customer</label>
            <input id="customer" name="customer" value="{{ old('customer', $record->customer) }}" placeholder="Customer name">
            @error('customer')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="contact_name">Contact Name</label>
            <input id="contact_name" name="contact_name" value="{{ old('contact_name', $record->contact_name) }}" placeholder="Primary contact">
            @error('contact_name')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="contact_phone">Contact Phone</label>
            <input id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $record->contact_phone) }}" placeholder="Phone number">
            @error('contact_phone')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="contact_email">Contact Email</label>
            <input id="contact_email" type="email" name="contact_email" value="{{ old('contact_email', $record->contact_email) }}" placeholder="name@example.com">
            @error('contact_email')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="warehouse">Warehouse</label>
            <input id="warehouse" name="warehouse" value="{{ old('warehouse', $record->warehouse) }}" placeholder="Warehouse or branch">
            @error('warehouse')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="source_warehouse">Source Warehouse</label>
            <input id="source_warehouse" name="source_warehouse" value="{{ old('source_warehouse', $record->source_warehouse) }}" placeholder="Source location">
            @error('source_warehouse')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="destination_warehouse">Destination Warehouse</label>
            <input id="destination_warehouse" name="destination_warehouse" value="{{ old('destination_warehouse', $record->destination_warehouse) }}" placeholder="Destination location">
            @error('destination_warehouse')<div class="error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="content-grid">
        <div class="field">
            <label for="reference_no">Reference No.</label>
            <input id="reference_no" name="reference_no" value="{{ old('reference_no', $record->reference_no) }}" placeholder="Reference number">
            @error('reference_no')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="purchase_order_no">Purchase Order No.</label>
            <input id="purchase_order_no" name="purchase_order_no" value="{{ old('purchase_order_no', $record->purchase_order_no) }}" placeholder="Purchase order number">
            @error('purchase_order_no')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="purchase_invoice_no">Purchase Invoice No.</label>
            <input id="purchase_invoice_no" name="purchase_invoice_no" value="{{ old('purchase_invoice_no', $record->purchase_invoice_no) }}" placeholder="Supplier invoice number">
            @error('purchase_invoice_no')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="sale_invoice_no">Sales Invoice No.</label>
            <input id="sale_invoice_no" name="sale_invoice_no" value="{{ old('sale_invoice_no', $record->sale_invoice_no) }}" placeholder="Invoice number">
            @error('sale_invoice_no')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="transfer_status">Transfer Status</label>
            <input id="transfer_status" name="transfer_status" value="{{ old('transfer_status', $record->transfer_status) }}" placeholder="Pending, In Transit, Completed">
            @error('transfer_status')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="adjustment_reason">Adjustment Reason</label>
            <input id="adjustment_reason" name="adjustment_reason" value="{{ old('adjustment_reason', $record->adjustment_reason) }}" placeholder="Damage, theft, expiry, counting error">
            @error('adjustment_reason')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="transaction_date">Transaction Date</label>
            <input id="transaction_date" type="date" name="transaction_date" value="{{ old('transaction_date', optional($record->transaction_date)->format('Y-m-d')) }}">
            @error('transaction_date')<div class="error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="content-grid">
        <div class="field">
            <label for="quantity">Quantity</label>
            <input id="quantity" type="number" min="0" name="quantity" value="{{ old('quantity', $record->quantity) }}" placeholder="0">
            @error('quantity')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="reserved_quantity">Reserved Quantity</label>
            <input id="reserved_quantity" type="number" min="0" name="reserved_quantity" value="{{ old('reserved_quantity', $record->reserved_quantity) }}" placeholder="0">
            @error('reserved_quantity')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="damaged_quantity">Damaged Quantity</label>
            <input id="damaged_quantity" type="number" min="0" name="damaged_quantity" value="{{ old('damaged_quantity', $record->damaged_quantity) }}" placeholder="0">
            @error('damaged_quantity')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="alert_threshold">Low Stock Threshold</label>
            <input id="alert_threshold" type="number" min="0" name="alert_threshold" value="{{ old('alert_threshold', $record->alert_threshold) }}" placeholder="Minimum stock level">
            @error('alert_threshold')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="suggested_reorder_quantity">Suggested Reorder Quantity</label>
            <input id="suggested_reorder_quantity" type="number" min="0" name="suggested_reorder_quantity" value="{{ old('suggested_reorder_quantity', $record->suggested_reorder_quantity) }}" placeholder="Reorder suggestion">
            @error('suggested_reorder_quantity')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="cost_price">Cost Price</label>
            <input id="cost_price" type="number" step="0.01" min="0" name="cost_price" value="{{ old('cost_price', $record->cost_price) }}" placeholder="0.00">
            @error('cost_price')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="selling_price">Selling Price</label>
            <input id="selling_price" type="number" step="0.01" min="0" name="selling_price" value="{{ old('selling_price', $record->selling_price) }}" placeholder="0.00">
            @error('selling_price')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="return_quantity">Return Quantity</label>
            <input id="return_quantity" type="number" min="0" name="return_quantity" value="{{ old('return_quantity', $record->return_quantity) }}" placeholder="0">
            @error('return_quantity')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="refund_amount">Refund Amount</label>
            <input id="refund_amount" type="number" step="0.01" min="0" name="refund_amount" value="{{ old('refund_amount', $record->refund_amount) }}" placeholder="0.00">
            @error('refund_amount')<div class="error">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="content-grid">
        <div class="field">
            <label for="status">Status</label>
            <input id="status" name="status" value="{{ old('status', $record->status) }}" placeholder="Draft, Active, Pending, Completed">
            @error('status')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label for="value">Value</label>
            <input id="value" name="value" value="{{ old('value', $record->value) }}" placeholder="Optional summary value">
            @error('value')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $record->is_active ?? true))>
                Active
            </label>
        </div>

        <div class="field">
            <label>
                <input type="hidden" name="prevent_negative_stock" value="0">
                <input type="checkbox" name="prevent_negative_stock" value="1" @checked(old('prevent_negative_stock', $record->prevent_negative_stock ?? false))>
                Prevent negative stock
            </label>
        </div>
    </div>

    <div class="field">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes" rows="5">{{ old('notes', $record->notes) }}</textarea>
        @error('notes')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div class="form-actions">
        <button class="button primary" type="submit">{{ $formMode === 'create' ? 'Create Inventory Record' : 'Update Inventory Record' }}</button>
        <a class="button secondary" href="{{ route('admin.inventory-records.index') }}">Cancel</a>
    </div>
</div>
