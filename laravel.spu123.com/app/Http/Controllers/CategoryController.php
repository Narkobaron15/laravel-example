<?php

namespace App\Http\Controllers;

use App\Models\Category;
use http\Message;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;
use Nette\Schema\ValidationException;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Category"},
     *     path="/api/categories",
     *     @OA\Response(response="200", description="List Categories.")
     * )
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $list = Category::all();
        return $this->jsonresponse($list);
    }

    /**
     * @OA\Post(
     *     tags={"Category"},
     *     path="/api/categories/create",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={},
     *                 @OA\Property(
     *                     property="image",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="201", description="Added a new category")
     * )
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $input = $this->getValidatedData($request, $this->categoryRules);

            $category = Category::create($input);
            return $this->jsonresponse($input, 201);
        }
        catch (Exception $e) {
            return $this->jsonresponse(["StatusCode" => 400, "Message" => "Invalid data. Fill in all fields"], 400);
        }
    }
}
