<?php

namespace App\Modules\Product\Http\Controllers;

use Dust\Base\Controller;
use Illuminate\Http\Request;
use Dust\Http\Router\Enum\Http;
use Dust\Http\Router\Attributes\Route;
use Dust\Base\Contracts\ResponseInterface;
use App\Modules\Product\Core\Services\ListProductsService;
use App\Modules\Product\Http\Requests\ListProductsRequest;
use App\Modules\Product\Http\Responses\ListProductsResponse;

#[Route(Http::GET, 'products', 'api.products.list')]
class ListProductsController extends Controller
{
    public function __construct(ListProductsResponse $response, ListProductsRequest $request, protected ListProductsService $service)
    {
        parent::__construct($response, $request);
    }

    public function handle(ResponseInterface $response, Request $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->service->paginate($request->get('per_page', 20));
    }
}
