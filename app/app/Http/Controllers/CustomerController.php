<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/customers",
     *     summary="List customers",
     *     tags={"Customers"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name or reference",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter by category (Gold, Silver, Bronze)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of customers"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('reference', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        return $query->withCount('contacts')->get();
    }

    /**
     * @OA\Post(
     *     path="/api/customers",
     *     summary="Create a new customer",
     *     tags={"Customers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "reference", "category"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="reference", type="string"),
     *             @OA\Property(property="category", type="string", enum={"Gold", "Silver", "Bronze"}),
     *             @OA\Property(property="start_date", type="string", format="date"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer created successfully"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'reference' => 'required|unique:customers,reference',
            'category' => 'required|in:Gold,Silver,Bronze',
            'start_date' => 'nullable|date',
            'description' => 'nullable'
        ]);

        return Customer::create($validated);
    }

    /**
     * @OA\Put(
     *     path="/api/customers/{customer}",
     *     summary="Update an existing customer",
     *     tags={"Customers"},
     *     @OA\Parameter(
     *         name="customer",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "reference", "category"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="reference", type="string"),
     *             @OA\Property(property="category", type="string", enum={"Gold", "Silver", "Bronze"}),
     *             @OA\Property(property="start_date", type="string", format="date"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer updated successfully"
     *     )
     * )
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required',
            'reference' => 'required|unique:customers,reference,' . $customer->id,
            'category' => 'required|in:Gold,Silver,Bronze',
            'start_date' => 'nullable|date',
            'description' => 'nullable'
        ]);

        $customer->update($validated);
        return $customer;
    }

    /**
     * @OA\Delete(
     *     path="/api/customers/{customer}",
     *     summary="Delete a customer and its associated contacts",
     *     description="Deletes the specified customer. Also deletes all related contacts if cascading is enabled.",
     *     operationId="destroyCustomer",
     *     tags={"Customers"},
     *     @OA\Parameter(
     *         name="customer",
     *         in="path",
     *         required=true,
     *         description="ID of the customer to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Customer deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found"
     *     )
     * )
     */

    public function destroy(Customer $customer)
    {
        $customer->delete(); 
        return response()->noContent();
    }
}
