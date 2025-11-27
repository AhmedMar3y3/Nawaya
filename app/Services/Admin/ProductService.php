<?php

namespace App\Services\Admin;

use App\Models\Product;
use App\Filters\ProductFilter;
use App\Enums\Boutique\OwnerType;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductService
{
    public function getProductsWithFilters(Request $request, int $perPage = 15, bool $onlyTrashed = false): LengthAwarePaginator
    {
        $query = $onlyTrashed ? Product::onlyTrashed() : Product::query();

        $filter = new ProductFilter($request);
        $query = $filter->apply($query);

        $query->with('userOwner');

        $query->latest();

        return $query->paginate($perPage);
    }

    public function getProductById(int $id): Product
    {
        /** @var Product $product */
        $product = Product::with('userOwner')
            ->findOrFail($id);
        
        return $product;
    }

    public function getProductStats(): array
    {
        return [
            'total' => Product::count(),
            'platform_owned' => Product::where('owner_type', OwnerType::PLATFORM->value)->count(),
            'user_owned' => Product::where('owner_type', OwnerType::USER->value)->count(),
            'deleted' => Product::onlyTrashed()->count(),
        ];
    }

    public function createProduct(array $data): Product
    {
        if ($data['owner_type'] === OwnerType::PLATFORM->value) {
            $data['owner_id'] = null;
            $data['owner_per'] = 0;
        }

        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data): bool
    {
        if ($data['owner_type'] === OwnerType::PLATFORM->value) {
            $data['owner_id'] = null;
            $data['owner_per'] = 0;
        }

        return $product->update($data);
    }

    public function deleteProduct(Product $product): bool
    {
        return $product->delete();
    }

    public function restoreProduct(int $id): bool
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        return $product->restore();
    }

    public function permanentlyDeleteProduct(int $id): bool
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        return $product->forceDelete();
    }
}

