<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product_InventoryStoreRequest;
use App\Http\Requests\Product_InventoryUpdateRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Product_Inventory;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Product_InventoryController extends Controller
{

    /**
     * Show all product inventories
     * @OA\Get (
     *     path="/api/product_inventories",
     *     tags={"Product Inventory"},
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
     *                         property="quantity",
     *                         type="number",
     *                         example="20"
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
            $product_inventories = Product_Inventory::all();

            return ApiResponse::success('Success', 200, $product_inventories);
        } catch (Exception $e) {
            return ApiResponse::error('An error ocurrer while trying to get the product inventories: ' . $e->getMessage(), 500);
        }
    }


    /**
     * Create a new product inventory
     * @OA\Post (
     *     path="/api/product_inventories",
     *     tags={"Product Inventory"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="quantity",
     *                          type="number"
     *                      )
     *                 ),
     *                 example={
     *                     "quantity":"20"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="quantity", type="number", example="20"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      )
     * )
     */
    public function store(Product_InventoryStoreRequest $request)
    {
        try {
            Product_Inventory::create($request->all());

            return ApiResponse::success('The product inventory has been successfully created', 201, $request->all());
        } catch (ValidationException $e) {
            return ApiResponse::error('validation errro: ' . $e->getMessage(), 422);
        }
    }

    /**
     * Show product inventory information
     * @OA\Get (
     *     path="/api/product_inventories/{id}",
     *     tags={"Product Inventory"},
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
     *              @OA\Property(property="name", type="number", example="quantity"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $product_inventories = Product_Inventory::findOrFail($id);

            return ApiResponse::success('Success', 200, $product_inventories);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error ocurred while trying to get the product inventory', 404);
        }
    }


    /**
     * Update product inventory information
     * @OA\Patch (
     *     path="/api/product_inventories/{id}",
     *     tags={"Product Inventory"},
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
     *                          property="quantity",
     *                          type="number"
     *                      )
     *                 ),
     *                 example={
     *                     "quantity":"20"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="quantity", type="number", example="20"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      )
     * )
     */
    public function update(Product_InventoryUpdateRequest $request, $id)
    {
        try {
            $product_inventory = Product_Inventory::findOrFail($id);
            $product_inventory->update($request->all());

            return ApiResponse::success('The product inventory has been successfully updated', 200, $request->all());
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error ocurred while trying to get the product inventory: ' . $e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error: ' . $e->getMessage(), 422);
        }
    }


    /**
     * Delete product inventory information
     * @OA\Delete (
     *     path="/api/product_inventories/{id}",
     *     tags={"Product Inventory"},
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
            $product_Inventory = Product_Inventory::findOrFail($id);

            $product_Inventory->delete();
            return ApiResponse::success('The product inventory has been successfully deleted', 204);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error ocurred while trying to get the product inventory: ' . $e->getMessage(), 404);
        }
    }
}
