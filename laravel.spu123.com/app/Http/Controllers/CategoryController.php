<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class CategoryController extends Controller
{
    // Code for model validation
    protected function ValidateCategory(array $input)
    {
        $rules = [
            'name.required' => 'Вкажіть назву категорії',
            // image is not required because it won't be validated correctly on update
            // 'image.required'=>'Вкажіть фото категорії',
            'description.required' => 'Вкажіть опис категорії',
            'name.max' => 'Поле занадто довге',
            'description.max' => 'Поле занадто довге',
            'image.mimes' => 'Виберіть картинку формату jpeg, jpg, png або gif',
            'image.max' => 'Картинка занадто великого розміру. Виберіть меншу за 50 МБ',
        ];

        // validate using validator
        return Validator::make($input, [
            'name' => 'required|string|max:200',
            'image' => 'mimes:jpeg,jpg,png,gif|max:50000',
            'description' => 'required|string|max:4000',
        ], $rules);
    }

    // Swagger commentary

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

    // Swagger commentary

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
     *                     type="file"
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
        // extracting all fields from multipart form
        $input = $request->all();
        // validating form data
        $validation = $this->ValidateCategory($input);
        if ($validation->fails()) {
            // sending 'error' response
            return $this->JsonResponse($validation->errors(), Response::HTTP_BAD_REQUEST);
        }

        // extracting category's image
        $input['image'] = $this->SaveImageToPath($request);

        // updating and sending the new category
        $category = Category::create($input);
        return $this->JsonResponse($category, Response::HTTP_CREATED);
    }

    // Swagger commentary

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
        // searching for the category by id in corresponding table
        // if not found, sending 'error' response
        try {
            $category = Category::findOrFail($id);
            return $this->JsonResponse($category);
        } catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }

    // Swagger commentary

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
     *                     type="file"
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

            // extracting all fields from multipart form
            $input = $request->all();

            // validating form data
            $validation = $this->ValidateCategory($input);
            if ($validation->fails()) {
                // sending 'error' response
                return $this->JsonResponse($validation->errors(), Response::HTTP_BAD_REQUEST);
            }

            // removing the current image
            if ($request->hasFile("image")) {
                $this->UnlinkImage($category->image);
            }
            // setting the new image if there is any
            $input['image'] = $this->SaveImageToPath($request) ?? $category['image'];

            // updating and sending the updated category
            $category->update($input);
            return $this->JsonResponse(Category::findOrFail($id)); // send updated data
        } catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }

    // Swagger commentary

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

            $this->UnlinkImage($category->image);
            $products = Product::where('category_id', $id)->get();
            if (is_countable($products)) {
                foreach ($products as $p) {
                    foreach ($p->images as $img) {
                        $this->UnlinkImage($img->name);
                        $img->delete();
                    }
                    $p->delete();
                }
            }
            $category->delete();

            return $this->JsonResponse("Ok");
        } catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }
}
