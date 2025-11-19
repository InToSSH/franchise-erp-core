<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Actions\Product;

use App\Domain\Catalog\Models\Product;
use Illuminate\Http\UploadedFile;
use Storage;

class UpdateProductImage
{
    public function execute(Product $product, UploadedFile $file): Product
    {
        if ($product->image_path) {
            Storage::disk('images')->delete($product->image_path);
        }

        $originalFilenameWithoutExtension = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = uniqid($originalFilenameWithoutExtension . '_', true) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('products', $filename,'images');
        $product->image_path = $path;
        $product->save();

        return $product;
    }
}
