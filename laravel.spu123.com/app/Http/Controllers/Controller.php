<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="SPU123 Shop",
 *      description="Demo my Project ",
 *      @OA\Contact(
 *          email="admin@gmail.com"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function JsonResponse($data, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $status,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_PRETTY_PRINT);
    }
    protected function ModelNotFoundResponse() : JsonResponse {
        return $this->JsonResponse(['message' => 'Not found', "status" => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
    }
}
