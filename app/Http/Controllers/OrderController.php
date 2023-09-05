<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Order;
use App\Models\Product;
use App\Models\Product_Inventory;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Show all orders
     * @OA\Get (
     *     path="/api/orders",
     *     tags={"Order"},
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
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="total",
     *                         type="double",
     *                         example="50.58"
     *                     ),
     *                     @OA\Property(
     *                         property="product_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="user_id",
     *                         type="number",
     *                         example="1"
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
            $orders = Order::all();

            return ApiResponse::success('Success', 200, $orders);
        } catch (Exception $e) {
            return ApiResponse::error('Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Create a new order
     * @OA\Post (
     *     path="/api/orders",
     *     tags={"Order"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
    *                      @OA\Property(
     *                         property="quantity",
     *                         type="number"
     *                     ),
     *                     @OA\Property(
     *                         property="product_id",
     *                         type="number"
     *                     ),
     *                     @OA\Property(
     *                         property="user_id",
     *                         type="number"
     *                     )
     *                 ),
     *                 example={
     *                     "quantity":"1",
     *                     "product_id":"1",
     *                     "user_id":"1"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="quantity", type="number", example="3"),
     *              @OA\Property(property="total", type="double", example="15"),
     *              @OA\Property(property="product_id", type="number", example="1"),
     *              @OA\Property(property="user_id", type="number", example="1"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      )
     * )
     */
    public function store(OrderStoreRequest $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);
            $inventory = Product_Inventory::findOrFail($product->inventory_id);

            if($request->quantity > $inventory->quantity) 
            {
                return ApiResponse::error('Sorry, the amount you want is more than we have', 400);
            }

            $total = $product->price * $request->quantity;

            $inventory->update([
                'quantity' => ($inventory->quantity - $request->quantity)
            ]);

            $order = Order::create([
                'quantity' => $request->quantity,
                'total' => $total,
                'product_id' => $request->product_id,
                'user_id' => $request->user_id
            ]);

            return ApiResponse::success('The order has been successfully created', 200, $order);

        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Error: ' .  $e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error('An error ocurred while trying to get the table: ' .  $e->getMessage(), 500);
        }
    }

    /**
     * Show order information
     * @OA\Get (
     *     path="/api/orders/{id}",
     *     tags={"Order"},
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
     *              @OA\Property(property="quantity", type="number", example="3"),
     *              @OA\Property(property="total", type="double", example="15"),
     *              @OA\Property(property="product_id", type="number", example="1"),
     *              @OA\Property(property="user_id", type="number", example="1"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $order = Order::with(['user', 'product'])->findOrFail($id);

            return ApiResponse::success('Success', 200, $order);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error ocurred while trying to get the order: ' . $e->getMessage(), 404);
        }
    }

    /**
     * Update order information
     * @OA\Patch (
     *     path="/api/orders/{id}",
     *     tags={"Order"},
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
     *                         property="quantity",
     *                         type="number"
     *                     ),
     *                     @OA\Property(
     *                         property="product_id",
     *                         type="number"
     *                     ),
     *                     @OA\Property(
     *                         property="user_id",
     *                         type="number"
     *                     )
     *                 ),
     *                 example={
     *                     "quantity":"1",
     *                     "product_id":"1",
     *                     "user_id":"1"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="quantity", type="number", example="3"),
     *              @OA\Property(property="total", type="double", example="15"),
     *              @OA\Property(property="product_id", type="number", example="1"),
     *              @OA\Property(property="user_id", type="number", example="1"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      )
     * )
     */
    public function update(OrderUpdateRequest $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $product = Product::findOrFail($request->product_id);
            $inventory = Product_Inventory::findOrFail($product->inventory_id);

            if($request->quantity > $inventory->quantity) 
            {
                return ApiResponse::error('Sorry, the amount you want is more than we have', 400);
            }

            $total = $product->price * $request->quantity;

            $inventory->update([
                'quantity' => ($inventory->quantity - $request->quantity)
            ]);

            $order->update([
                'quantity' => $request->quantity,
                'total' => $total,
                'product_id' => $request->product_id,
                'user_id' => $request->user_id
            ]);

            return ApiResponse::success('The order has been successfully updated', 200, $order);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error ocurred while trying to get the order: ' . $e->getMessage(), 404);
        }
    }

   /**
     * Delete order information
     * @OA\Delete (
     *     path="/api/orders/{id}",
     *     tags={"Order"},
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
            $order = Order::findOrFail($id);
            $order->delete();

            return ApiResponse::success('The order has been successfully deleted', 204);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error ocurred while trying to get the order: ' . $e->getMessage(), 404);
        }
    }
}
