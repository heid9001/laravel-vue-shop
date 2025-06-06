<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::with('category')->paginate(2));
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        return new ProductResource($product);
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load('category'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json(['message' => 'Товар успешно удален']);
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Невозможно удалить товар, так как он связан с существующими заказами'
                ], 422);
            }
            return response()->json([
                'message' => 'Не удалось удалить товар'
            ], 500);
        }
    }
}
