@csrf

<div class="form">
    <div class="field">
        <label for="name">Name</label>
        <input id="name" name="name" value="{{ old('name', $garment->name) }}" required>
        @error('name')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="category">Category</label>
        <input id="category" name="category" value="{{ old('category', $garment->category) }}" required>
        @error('category')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="price">Price</label>
        <input id="price" name="price" type="number" step="0.01" value="{{ old('price', $garment->price) }}" required>
        @error('price')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="stock">Stock</label>
        <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $garment->stock ?? 0) }}" required>
        @error('stock')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4">{{ old('description', $garment->description) }}</textarea>
        @error('description')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div class="field">
        <label>
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $garment->is_active ?? true))>
            Active in frontend catalog
        </label>
        @error('is_active')<div class="error">{{ $message }}</div>@enderror
    </div>

    <div class="actions">
        <button class="button primary" type="submit">{{ $buttonText }}</button>
        <a class="button secondary" href="{{ route('admin.garments.index') }}">Cancel</a>
    </div>
</div>
