<?php

namespace App\Modules\Product\Http\Controllers;

use Dust\Base\Controller;
use Illuminate\Http\Request;
use Dust\Http\Router\Enum\Http;
use Dust\Http\Router\Attributes\Route;
use Dust\Base\Contracts\ResponseInterface;
use App\Modules\Product\Core\Services\GetProductService;
use App\Modules\Product\Http\Requests\GetProductRequest;
use App\Modules\Product\Http\Responses\GetProductResponse;

#[Route(Http::GET, 'products/{product}', 'api.products.get')]
class GetProductController extends Controller
{
    public function __construct(GetProductResponse $response, GetProductRequest $request, protected GetProductService $service)
    {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): mixed
    {
        return $this->service->get($request->route('product'));
    }
}
