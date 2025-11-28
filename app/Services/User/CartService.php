<?php

namespace App\Services\User;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function addToCart(User $user, int $productId, int $quantity): Cart
    {
        $product = Product::find($productId);

        if (! $product) {
            throw new \Exception('المنتج غير موجود');
        }

        if ($product->trashed()) {
            throw new \Exception('لا يمكن إضافة منتج محذوف إلى السلة');
        }

        DB::beginTransaction();

        try {
            $cart     = $user->cart ?? Cart::create(['user_id' => $user->id, 'total_price' => 0]);
            $cartItem = $cart->cartItems()->where('product_id', $productId)->first();

            if ($cartItem) {
                $cartItem->update([
                    'quantity' => $cartItem->quantity + $quantity,
                    'price'    => $product->price,
                ]);
            } else {
                $cart->cartItems()->create([
                    'product_id' => $productId,
                    'quantity'   => $quantity,
                    'price'      => $product->price,
                ]);
            }

            $this->updateCartTotalPrice($cart);
            DB::commit();

            return $this->loadCartWithItems($cart);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateCart(User $user, array $items): Cart
    {
        $cart = $user->cart;

        if (! $cart) {
            throw new \Exception('السلة غير موجودة');
        }

        DB::beginTransaction();

        try {
            foreach ($items as $item) {
                $cartItem = $cart->cartItems()->find($item['cart_item_id']);

                if (! $cartItem) {
                    continue;
                }

                if ($item['quantity'] == 0) {
                    $cartItem->delete();
                } else {
                    $product = $cartItem->product;

                    if ($product->trashed()) {
                        $cartItem->delete();
                        continue;
                    }

                    $cartItem->update([
                        'quantity' => $item['quantity'],
                        'price'    => $product->price,
                    ]);
                }
            }

            $this->updateCartTotalPrice($cart);
            DB::commit();

            return $this->loadCartWithItems($cart);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function updateCartTotalPrice(Cart $cart): void
    {
        $totalPrice = $cart->cartItems()
            ->whereHas('product', function ($query) {
                $query->withoutTrashed();
            })
            ->get()
            ->sum(function ($item) {
                return $item->price * $item->quantity;
            });

        $cart->update(['total_price' => $totalPrice]);
    }

    private function loadCartWithItems(Cart $cart): Cart
    {
        return $cart->load([
            'cartItems'         => function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->withoutTrashed();
                });
            },
            'cartItems.product' => function ($query) {
                $query->withoutTrashed();
            },
        ]);
    }

    public function getFullCartSummary(User $user): array
    {
        $items  = $this->getCartItemsSummary($user);
        $prices = $this->getCartPricesSummary($items);

        return [
            'items'  => $items,
            'prices' => $prices,
        ];
    }

    public function getCartItemsSummary(User $user)
    {
        $cart = $user->cart;

        if (! $cart) {
            return collect([]);
        }

        return $cart->cartItems()
            ->whereHas('product', function ($query) {
                $query->withoutTrashed();
            })
            ->with(['product' => function ($query) {
                $query->withoutTrashed();
            }])
            ->get();
    }

    public function getCartPricesSummary($items): array
    {
        $productsPrice = $items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $tax        = $productsPrice * 0.05;
        $totalPrice = $productsPrice + $tax;

        return [
            'products_price' => round($productsPrice, 2),
            'tax'            => round($tax, 2),
            'total_price'    => round($totalPrice, 2),
        ];
    }

    public function deleteCartItem(User $user, int $cartItemId): Cart
    {
        $cart = $user->cart;

        if (! $cart) {
            throw new \Exception('السلة غير موجودة');
        }

        DB::beginTransaction();

        try {
            $cartItem = $cart->cartItems()->find($cartItemId);

            if (! $cartItem) {
                throw new \Exception('عنصر السلة غير موجود');
            }

            $cartItem->delete();

            $this->updateCartTotalPrice($cart);

            DB::commit();

            return $this->loadCartWithItems($cart);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
