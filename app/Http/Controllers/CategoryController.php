<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryResourceFull;
use App\Http\Responses\ApiResponse;
use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{

   /**
     * Show all categories
     * @OA\Get (
     *     path="/api/categories",
     *     tags={"Category"},
     *     summary="Get all categories",
     *     operationId="Categories",
     *     security={{"token": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Dairy"
     *                     ),
     *                     @OA\Property(
     *                         property="description",
     *                         type="string",
     *                         example="This category is all about dairy."
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2023-02-23T00:09:16.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2023-02-23T12:33:45.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            return ApiResponse::success('Success', 200, CategoryResource::collection(Category::all()));
        } catch (Exception $e) {
            return ApiResponse::error('An error ocurred while trying to get the users ' . $e->getMessage(), 500);
        }
    }

   /**
     * Create a new category
     * @OA\Post (
     *     path="/api/categories",
     *     tags={"Category"},
     *     security={{"token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"Dairy",
     *                     "description":"This category is all about dairy."
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Dairy"),
     *              @OA\Property(property="description", type="string", example="This category is all about dairy."),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      )
     * )
     */
    public function store(CategoryStoreRequest $request)
    {
        try {
            return ApiResponse::success('The category has been successfully created', 201, new CategoryResource(Category::create($request->all())));
        } catch (ValidationException $e) {
            return ApiResponse::error('Validation error: ' . $e->getMessage(), 422);
        }
    }

   /**
     * Show category information
     * @OA\Get (
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Dairy"),
     *              @OA\Property(property="description", type="string", example="This category is all about dairy."),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            return ApiResponse::success('Success', 200, new CategoryResourceFull(Category::findOrFail($id)));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error ocurred while trying to get the category: ' . $e->getMessage(), 404);
        }
    }

    /**
     * Show category products information
     * @OA\Get (
     *     path="/api/categories/{id}/products",
     *     tags={"Category"},
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Dairy"),
     *              @OA\Property(property="description", type="string", example="This category is all about dairy."),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     )
     * )
     */
    public function getProductsByCategoryId($id)
    {
        try {
            $category = Category::with('products')->findOrFail($id);

            return ApiResponse::success('Success', 200, $category);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error ocurred while trying to get the category: ' . $e->getMessage(), 404);
        }
    }

   /**
     * Update category information
     * @OA\Patch (
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"Dairy",
     *                     "description":"This category is all about dairy."
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Dairy"),
     *              @OA\Property(property="description", type="string", example="This category is all about dairy."),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      )
     * )
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->update($request->all());

            return ApiResponse::success('The category has been successfully updated ', 200, new CategoryResource(Category::findOrFail($id)));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error ocurred while trying to get the category: ' . $e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error: ' . $e->getMessage(), 422);
        }
    }

   /**
     * Delete category information
     * @OA\Delete (
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     security={{"token": {}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="NO CONTENT"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return ApiResponse::success('The category has been successfully deleted', 204);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error ocurred while trying to get the user: ' . $e->getMessage(), 404);
        }
    }
}
