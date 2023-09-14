<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductResourceFull;
use App\Http\Responses\ApiResponse;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{

    /**
     * Show all products
     * @OA\Get (
     *     path="/api/products",
     *     tags={"Product"},
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
     *                         example="Battery"
     *                     ),
     *                     @OA\Property(
     *                         property="description",
     *                         type="string",
     *                         example="This is the description of the battery"
     *                     ),
     *                     @OA\Property(
     *                         property="price",
     *                         type="double",
     *                         example="50.75"
     *                     ),
     *                     @OA\Property(
     *                         property="category_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="inventory_id",
     *                         type="number",
     *                         example="15"
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
            return ApiResponse::success('Success', 200, ProductResource::collection(Product::all()));
        } catch (Exception $e) {
            return ApiResponse::error('An ocurrer ocurred while trying to get the users: ' . $e->getMessage(), 500);
        }
    }

   /**
     * Create a new product
     * @OA\Post (
     *     path="/api/products",
     *     tags={"Product"},
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
     *                      ),
     *                      @OA\Property(
     *                          property="price",
     *                          type="double"
     *                      ),
     *                      @OA\Property(
     *                          property="category_id",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="inventory_id",
     *                          type="number"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"Battery",
     *                     "description":"This is the description of the battery",
     *                     "price":"50.75",
     *                     "category_id":"1",
     *                     "inventory_id":"15"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Battery"),
     *              @OA\Property(property="description", type="string", example="This is the description of the battery"),
     *              @OA\Property(property="price", type="double", example="50.75"),
     *              @OA\Property(property="category_id", type="number", example="1"),
     *              @OA\Property(property="inventory_id", type="number", example="15"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      )
     * )
     */
    public function store(ProductStoreRequest $request)
    {
        try {
            $product = Product::create($request->all());

            return ApiResponse::success('The product has been successfully created', 201, new ProductResource($product));
        } catch (ValidationException $e) {
            return ApiResponse::error('Validation error: ' . $e->getMessage(), 422);
        }
    }

   /**
     * Show product information
     * @OA\Get (
     *     path="/api/products/{id}",
     *     tags={"Product"},
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
     *              @OA\Property(property="name", type="string", example="Battery"),
     *              @OA\Property(property="description", type="string", example="This is the description of the battery"),
     *              @OA\Property(property="price", type="double", example="50.75"),
     *              @OA\Property(property="category_id", type="number", example="1"),
     *              @OA\Property(property="inventory_id", type="number", example="15"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $product = Product::with(['category', 'inventory', 'orders'])->findOrFail($id);
            
            return ApiResponse::success('Success', 200, new ProductResourceFull($product));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error ocurred while trying to get the product: ' . $e->getMessage(), 404);
        }
    }


    /**
     * Update product information
     * @OA\Patch (
     *     path="/api/products/{id}",
     *     tags={"Product"},
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
     *                      ),
     *                      @OA\Property(
     *                          property="price",
     *                          type="double"
     *                      ),
     *                      @OA\Property(
     *                          property="category_id",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          property="inventory_id",
     *                          type="number"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"Battery",
     *                     "description":"This is the description of the battery",
     *                     "price":"50.75",
     *                     "category_id":"1",
     *                     "inventory_id":"15"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="Battery"),
     *              @OA\Property(property="description", type="string", example="This is the description of the battery"),
     *              @OA\Property(property="price", type="double", example="50.75"),
     *              @OA\Property(property="category_id", type="number", example="1"),
     *              @OA\Property(property="inventory_id", type="number", example="15"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      )
     * )
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update($request->all());
            
            return ApiResponse::success('The product has been successfully updated', 200, new ProductResource($product));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error ocurred while trying to get the product: ' . $e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error: ' . $e->getMessage(), 422);
        }
    }


   /**
     * Delete product information
     * @OA\Delete (
     *     path="/api/products/{id}",
     *     tags={"Product"},
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
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return ApiResponse::success('The product has been successfully deleted', 204);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error ocurred while trying to get the product: ' . $e->getMessage(), 404);
        }
    }
}
