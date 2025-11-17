<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Catalog\Models\Product;
use App\Domain\Catalog\Resources\ProductOptionsResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {

    }

    public function search(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        if ($request->has('exactValue')) {
            $product = Product::find((intval($request->query('exactValue'))));
            if ($product) {
                return response()->json([
                    'items' => [new ProductOptionsResource($product)->toArray($request)],
                ]);
            } else {
                return response()->json([
                    'items' => [],
                ]);
            }
        }

        $searchTerm = $request->query('query', '');
        $limit = $request->has('limit') ? (int) $request->query('limit') : 15;

        $query = Product::limit($limit);

        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('sku', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $products = $query->get();

        return response()->json([
            'items' => ProductOptionsResource::collection($products)->toArray($request),
        ]);
    }
}
