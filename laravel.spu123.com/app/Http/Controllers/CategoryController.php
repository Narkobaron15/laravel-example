<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    private function ValidateCategory(Request $request) {
        $input = $request->all();
        $message = [
            'name.required'=>'Вкажіть назву категорії',
            'image.required'=>'Вкажіть фото категорії',
            'description.required'=>'Вкажіть опис категорії'
        ];
        return Validator::make($input,[
            'name'=>'required',
            'image'=>'required',
            'description'=>'required',
        ],$message);
    }

    /**
     * @OA\Get(
     *     tags={"Category"},
     *     path="/api/categories",
     *     @OA\Response(response="200", description="List Categories.")
     * )
     */
    public function index(): JsonResponse
    {
        $list = Category::all();
        return $this->JsonResponse($list);
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
    public function create(Request $request): JsonResponse
    {
        $validation = $this->ValidateCategory($request);
        if($validation->fails()){
            return $this->JsonResponse($validation->errors(), 400);
        }

        $category = Category::create($request->all());
        return $this->JsonResponse($category, 201);
    }

    /**
     * @OA\Get(
     *     tags={"Category"},
     *     path="/api/categories/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Ідентифікатор категорії",
     *         required=true,
     *         @OA\Schema(
     *             type="number",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="List Categories."),
     * @OA\Response(
     *    response=404,
     *    description="Wrong id",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong Category Id has been sent. Pls try another one.")
     *        )
     *     )
     * )
     */
    public function getById($id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);
            return $this->JsonResponse($category);
        }
        catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }

    /**
     * @OA\Post(
     *     tags={"Category"},
     *     path="/api/categories/edit/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Ідентифікатор категорії",
     *         required=true,
     *         @OA\Schema(
     *             type="number",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name"},
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
     *     @OA\Response(response="200", description="Edited Category")
     * )
     */
    public function put($id, Request $request): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);

            $validation = $this->ValidateCategory($request);
            if($validation->fails()){
                return $this->JsonResponse($validation->errors(), 400);
            }

            $category->update($request->all());
            return $this->JsonResponse($category);
        }
        catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Ідентифікатор категорії",
     *         required=true,
     *         @OA\Schema(
     *             type="number",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успішне видалення категорії"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Категорії не знайдено"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизований"
     *     )
     * )
     */
    public function delete($id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return $this->JsonResponse("Ok");
        }
        catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }
}
