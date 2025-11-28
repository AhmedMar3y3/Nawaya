<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Admin\ProductService;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Http\Requests\Admin\Product\ProductFilterRequest;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function index(ProductFilterRequest $request): View
    {
        $tab     = $request->get('tab', 'active');
        $section = $request->get('section', 'products');

        $activeProducts  = $this->productService->getProductsWithFilters($request, 15, false);
        $deletedProducts = $this->productService->getProductsWithFilters($request, 15, true);

        $products = $tab === 'deleted' ? $deletedProducts : $activeProducts;

        $stats = $this->productService->getProductStats();
        $users = \App\Models\User::get(['id', 'full_name']);

        return view('Admin.products.index', compact('products', 'activeProducts', 'deletedProducts', 'stats', 'tab', 'section', 'users'));
    }

    public function show($id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);

            return response()->json([
                'success'   => true,
                'modalType' => 'show',
                'product'   => [
                    'id'         => $product->id,
                    'title'      => $product->title,
                    'price'      => $product->price,
                    'image'      => $product->image ? asset($product->image) : null,
                    'owner_type' => $product->owner_type->value,
                    'owner_id'   => $product->owner_id,
                    'owner_per'  => $product->owner_per,
                    'owner'      => $product->userOwner ? ['id' => $product->userOwner->id, 'full_name' => $product->userOwner->full_name] : null,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات المنتج',
            ], 404);
        }
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'success'   => true,
            'modalType' => 'create',
        ]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $this->productService->createProduct($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المنتج بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء المنتج: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);
            return response()->json([
                'success'   => true,
                'modalType' => 'edit',
                'product'   => [
                    'id'         => $product->id,
                    'title'      => $product->title,
                    'price'      => $product->price,
                    'image'      => $product->image ? asset($product->image) : null,
                    'owner_type' => $product->owner_type->value,
                    'owner_id'   => $product->owner_id,
                    'owner_per'  => $product->owner_per,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات المنتج',
            ], 404);
        }
    }

    public function update(UpdateProductRequest $request, $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $this->productService->updateProduct($product, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المنتج بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث المنتج: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $this->productService->deleteProduct($product);
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المنتج بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المنتج',
            ], 500);
        }
    }

    public function restore($id): JsonResponse
    {
        try {
            $this->productService->restoreProduct($id);
            return response()->json([
                'success' => true,
                'message' => 'تم استعادة المنتج بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استعادة المنتج',
            ], 500);
        }
    }

    public function permanentlyDelete($id): JsonResponse
    {
        try {
            $this->productService->permanentlyDeleteProduct($id);
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المنتج نهائياً بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المنتج',
            ], 500);
        }
    }
}
