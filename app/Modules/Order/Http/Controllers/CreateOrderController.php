<?php

namespace App\Modules\Order\Http\Controllers;

use Dust\Base\Controller;
use Illuminate\Http\Request;
use Dust\Http\Router\Enum\Http;
use Dust\Http\Router\Attributes\Route;
use Dust\Base\Contracts\ResponseInterface;
use App\Modules\Order\Core\Services\CreateOrderService;
use App\Modules\Order\Http\Requests\CreateOrderRequest;
use App\Modules\Order\Http\Responses\CreateOrderResponse;

#[Route(Http::POST, 'orders', 'api.orders.create')]
class CreateOrderController extends Controller
{
    public function __construct(CreateOrderResponse $response, CreateOrderRequest $request, protected CreateOrderService $service)
    {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): \App\Modules\Order\Domain\Entities\Order
    {
        return $this->service->create($request->get('products'));
    }
}
