<?php

namespace App\Http\Controllers\User;

use App\Traits\HttpResponses;
use App\Services\User\CartService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;
use App\Http\Requests\API\Cart\AddToCartRequest;
use App\Http\Resources\Cart\CartSummaryResource;
use App\Http\Requests\API\Cart\UpdateCartRequest;
use App\Http\Requests\API\Cart\DeleteCartItemRequest;

class CartController extends Controller
{
    use HttpResponses;

    public function __construct(
        private readonly CartService $cartService,
    ) {}

    public function addToCart(AddToCartRequest $request)
    {
        try {
            $user = auth()->user();
            $cart = $this->cartService->addToCart($user,$request->product_id,$request->quantity);
            return $this->successWithDataResponse(CartResource::make($cart));
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }

    public function updateCart(UpdateCartRequest $request)
    {
        try {
            $user = auth()->user();
            $cart = $this->cartService->updateCart($user,$request->items);

            return $this->successWithDataResponse(CartResource::make($cart));
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }

    public function getCartSummary()
    {
        try {
            $user    = auth()->user();
            $summary = $this->cartService->getFullCartSummary($user);

            return $this->successWithDataResponse(CartSummaryResource::make($summary));
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }

    public function deleteCartItem(DeleteCartItemRequest $request)
    {
        try {
            $user = auth()->user();
            $cart = $this->cartService->deleteCartItem($user, $request->cart_item_id);

            return $this->successWithDataResponse(CartResource::make($cart));
        } catch (\Exception $e) {
            return $this->failureResponse($e->getMessage());
        }
    }
}
