<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class PreviewController extends Controller
{
    public function __invoke(Product $product)
    {
        abort_unless($product->is_active && $product->hasPreview(), 404);

        return response()->file(Storage::disk('local')->path($product->preview_path));
    }
}
