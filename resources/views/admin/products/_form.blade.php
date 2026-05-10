<div class="form-grid">
    <div>
        <label for="title">Title</label>
        <input id="title" type="text" name="title" value="{{ old('title', $product->title) }}">
    </div>

    <div>
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="8">{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="two-up">
        <div>
            <label for="category">Category</label>
            <select id="category" name="category">
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(old('category', $product->category) === $category)>{{ ucfirst($category) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="price_usd">Price in USD</label>
            <input id="price_usd" type="number" step="0.01" min="0.01" name="price_usd" value="{{ old('price_usd', $product->price_usd) }}">
        </div>
    </div>

    <div class="two-up">
        <div class="upload-field">
            <label for="product_file">Product file</label>
            <input id="product_file" type="file" name="product_file">
            @if ($product->exists)
                <p style="margin-top: 0.75rem; font-size: 13px;">Current file: <span class="mono">{{ $product->file_path }}</span></p>
            @endif
        </div>
        <div class="upload-field">
            <label for="preview_file">Preview file</label>
            <input id="preview_file" type="file" name="preview_file">
            @if ($product->preview_path)
                <p style="margin-top: 0.75rem; font-size: 13px;">Current preview: <span class="mono">{{ $product->preview_path }}</span></p>
            @endif
        </div>
    </div>

    <label style="display: flex; align-items: center; gap: 10px; margin: 0;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true)) style="width: 16px; height: 16px; margin: 0;">
        <span style="font-size: 14px; color: var(--ink-muted);">Product is active and visible in the store</span>
    </label>
</div>

