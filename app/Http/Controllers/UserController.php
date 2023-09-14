<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceFull;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
   /**
     * Show all users
     * @OA\Get (
     *     path="/api/users",
     *     tags={"User"},
     *     security={{"token": {}}},
     *     summary="Get list of users",
     *     description="Return list of users",
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     * )
     */
    public function index()
    {
        try {
            return ApiResponse::success('Success', 200, UserResource::collection(User::all()));
        } catch (Exception $e) {
            return ApiResponse::error('An error occurred while trying to get the users: ' . $e->getMessage(), 500);
        }
    }

   /**
     * Create a new user
     * @OA\Post (
     *     path="/api/users",
     *     tags={"User"},
     *     security={{"token": {}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="username",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="first_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="last_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="telephone",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="age",
     *                          type="number"
     *                      )
     *                 ),
     *                 example={
     *                     "username":"Tommy 11",
     *                     "first_name":"Tommy",
     *                     "last_name":"Grullón Contreras",
     *                     "telephone":"829-754-6150",
     *                     "age":20
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="username", type="string", example="Tommy 11"),
     *              @OA\Property(property="first_name", type="string", example="Tommy"),
     *              @OA\Property(property="last_name", type="string", example="Grullón Contreras"),
     *              @OA\Property(property="telephone", type="string", example="829-754-6150"),
     *              @OA\Property(property="age", type="number", example=20),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      )
     * )
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $user = User::create($request->all());

            return ApiResponse::success('The users has been successfully created', 201, new UserResourceFull($user));
        } catch (ValidationException $e) {
            return ApiResponse::error('Validation error: ' . $e->getMessage(), 422);
        }
    }

    /**
     * Show user information
     * @OA\Get (
     *     path="/api/users/{id}",
     *     tags={"User"},
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
     *              @OA\Property(property="username", type="string", example="Tommy 11"),
     *              @OA\Property(property="first_name", type="string", example="Tommy"),
     *              @OA\Property(property="last_name", type="string", example="Grullón Contreras"),
     *              @OA\Property(property="telephone", type="string", example="829-754-6150"),
     *              @OA\Property(property="age", type="int", example="20"),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        try {
            return ApiResponse::success('Success', 200, new UserResourceFull(User::findOrFail($id)));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error occurred while trying to get the user: ' . $e->getMessage(), 404);
        }
    }

   /**
     * Update user information
     * @OA\Patch (
     *     path="/api/users/{id}",
     *     tags={"User"},
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
     *                          property="username",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="first_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="last_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="telephone",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="age",
     *                          type="number"
     *                      )
     *                 ),
     *                 example={
     *                     "username":"Tommy 11",
     *                     "first_name":"Tommy",
     *                     "last_name":"Grullón Contreras",
     *                     "telephone":"829-754-6150",
     *                     "age":20
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="CREATED",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="username", type="string", example="Tommy 11"),
     *              @OA\Property(property="first_name", type="string", example="Tommy"),
     *              @OA\Property(property="last_name", type="string", example="Grullón Contreras"),
     *              @OA\Property(property="telephone", type="string", example="829-754-6150"),
     *              @OA\Property(property="age", type="number", example=20),
     *              @OA\Property(property="created_at", type="string", example="2023-02-23T00:09:16.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2023-02-23T12:33:45.000000Z")
     *          )
     *      )
     * )
     */
    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->all());

            return ApiResponse::success('The user has been successfully updated', 200, new UserResourceFull(User::findOrFail($id)));
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error occurred while trying to get the user: ' . $e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error: ' . $e->getMessage(), 422);
        } 
    }

    /**
     * Delete user information
     * @OA\Delete (
     *     path="/api/users/{id}",
     *     tags={"User"},
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
            $user = User::findOrFail($id);
            $user->delete();

            return ApiResponse::success('The user has been successfully deleted', 204);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error occurred while trying to get the user: ' . $e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error: ' . $e->getMessage(), 422);
        } 
    }
}
