<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
* @OA\Info(
*             title="E-commerce Api", 
*             version="1.0",
*             description="This is project is about a simple E-commerce API"
* )
*
* @OA\Server(url="http://127.0.0.1:8000")
*/
class UserController extends Controller
{
   /**
     * This endpoint shows all users
     * @OA\Get (
     *     path="/api/users",
     *     tags={"User"},
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
     *                         property="username",
     *                         type="string",
     *                         example="Tommy 11"
     *                     ),
     *                     @OA\Property(
     *                         property="first_name",
     *                         type="string",
     *                         example="Tommy"
     *                     ),
     *                     @OA\Property(
     *                         property="last_name",
     *                         type="string",
     *                         example="Grullón Contreras"
     *                     ),
     *                     @OA\Property(
     *                         property="telephone",
     *                         type="string",
     *                         example="829-754-6150"
     *                     ),
     *                     @OA\Property(
     *                         property="age",
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
            $users = User::all();

            return ApiResponse::success('Success', 200, $users);
        } catch (Exception $e) {
            return ApiResponse::error('An error occurred while trying to get the users: ' . $e->getMessage(), 500);
        }
    }

   /**
     * Create a new user
     * @OA\Post (
     *     path="/api/users",
     *     tags={"User"},
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
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="UNPROCESSABLE CONTENT",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The apellidos field is required."),
     *              @OA\Property(property="errors", type="string", example="Objeto de errores"),
     *          )
     *      )
     * )
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $request->validated();
            $user = User::create($request->all());

            return ApiResponse::success('The users has been successfully created', 201, $user);
        } catch (ValidationException $e) {
            return ApiResponse::error('Validation error: ' . $e->getMessage(), 422);
        }
    }

    /**
     * Show user information
     * @OA\Get (
     *     path="/api/users/{id}",
     *     tags={"User"},
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
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="NOT FOUND",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Cliente] #id"),
     *          )
     *      )
     * )
     */
    public function show(int $id)
    {
        try {
            $user = User::findOrFail($id);

            return ApiResponse::success('Success', 200, $user);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error occurred while trying to get the user: ' . $e->getMessage(), 404);
        }
    }

/**
     * Update user information
     * @OA\Patch (
     *     path="/api/users/{id}",
     *     tags={"User"},
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
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="UNPROCESSABLE CONTENT",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The apellidos field is required."),
     *              @OA\Property(property="errors", type="string", example="Objeto de errores"),
     *          )
     *      )
     * )
     */
    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->all());

            return ApiResponse::success('The user has been successfully updated', 200, $request->all());
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('An error occurred while trying to get the user: ' . $e->getMessage(), 404);
        } catch (Exception $e) {
            return ApiResponse::error('Error: ' . $e->getMessage(), 422);
        } 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
