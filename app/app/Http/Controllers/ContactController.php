<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/customers/{customer}/contacts",
     *     summary="List contacts for a customer",
     *     tags={"Contacts"},
     *     @OA\Parameter(
     *         name="customer",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of contacts for the customer"
     *     )
     * )
     */
    public function index(Customer $customer)
    {
        return $customer->contacts;
    }

    /**
     * @OA\Post(
     *     path="/api/customers/{customer}/contacts",
     *     summary="Create a contact for a customer",
     *     tags={"Contacts"},
     *     @OA\Parameter(
     *         name="customer",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name"},
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Contact created successfully"
     *     )
     * )
     */
    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'nullable'
        ]);

        return $customer->contacts()->create($validated);
    }

    /**
     * @OA\Put(
     *     path="/api/contacts/{contact}",
     *     summary="Update an existing contact",
     *     tags={"Contacts"},
     *     @OA\Parameter(
     *         name="contact",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name"},
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contact updated successfully"
     *     )
     * )
     */
    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'nullable'
        ]);

        $contact->update($validated);
        return $contact;
    }

    /**
     * @OA\Delete(
     *     path="/api/contacts/{contact}",
     *     summary="Delete a contact",
     *     tags={"Contacts"},
     *     @OA\Parameter(
     *         name="contact",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Contact deleted successfully"
     *     )
     * )
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->noContent();
    }
}
