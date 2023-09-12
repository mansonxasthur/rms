<?php

namespace App\Modules\Order\Http\Responses;

use Throwable;
use Dust\Base\Response;
use Illuminate\Http\JsonResponse;
use App\Modules\Order\Domain\Entities\Order;
use App\Modules\Order\Core\Events\OrderCreated;
use App\Modules\Order\Http\Resources\OrderResource;
use Symfony\Component\HttpFoundation\Response as SymphonyResponse;
use App\Modules\Order\Core\Exceptions\InsufficientIngredientStockLevel;

class CreateOrderResponse extends Response
{
    /**
     * @param  Order  $resource
     */
    protected function createResource(mixed $resource): JsonResponse
    {
        return (new OrderResource($resource))
            ->response()
            ->setStatusCode(SymphonyResponse::HTTP_CREATED);
    }

    /**
     * @param  Order  $resource
     */
    protected function success(mixed $resource): void
    {
        event(new OrderCreated($resource));
    }

    protected function handleErrorResponse(Throwable $e): bool|JsonResponse
    {
        if ($e instanceof InsufficientIngredientStockLevel) {
            return response()->json(['message' => 'Insufficient ingredients to prepare the order!'], SymphonyResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return parent::handleErrorResponse($e);
    }
}
