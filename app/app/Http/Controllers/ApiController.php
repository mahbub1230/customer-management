<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Customer Management API",
 *     version="1.0.0",
 *     description="This is the API documentation for managing customers and their contacts.",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 */
class ApiController extends Controller
{
    // This controller is only for Swagger documentation
}
