<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Contacts",
 *     description="Operations related to contacts"
 * )
 */
class ContactController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/contacts",
     *     summary="Get list of contacts",
     *     security={{"bearerAuth": {}}},
     *     tags={"Contacts"},
     *     @OA\Response(response=200, description="Successful response")
     * )
     */
    public function index()
    {
        return response()->json(Contact::all());
    }

    /**
     * @OA\Post(
     *     path="/api/contacts",
     *     summary="Create a new contact",
     *     security={{"bearerAuth": {}}},
     *     tags={"Contacts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"customer_id", "type", "from", "to"},
     *             @OA\Property(property="external_id", type="string"),
     *             @OA\Property(property="customer_id", type="integer"),
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="from", type="string"),
     *             @OA\Property(property="to", type="string"),
     *             @OA\Property(property="subject", type="string"),
     *             @OA\Property(property="details", type="string"),
     *             @OA\Property(property="contacted_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Contact created"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'external_id'  => 'nullable|string|unique:contacts,external_id',
            'customer_id'  => 'required|exists:customers,id',
            'type'         => 'required|string|max:191',
            'from'         => 'required|string|max:191',
            'to'           => 'required|string|max:191',
            'subject'      => 'nullable|string|max:191',
            'details'      => 'nullable|string',
            'contacted_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $contact = Contact::create($validator->validated());

        return response()->json($contact, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/contacts/{id}",
     *     summary="Get a specific contact",
     *     security={{"bearerAuth": {}}},
     *     tags={"Contacts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful response"),
     *     @OA\Response(response=404, description="Contact not found")
     * )
     */
    public function show(Request $request, $id)
    {
        $contact = Contact::find($id);

        if ($contact) {
            return response()->json($contact);
        } else {
            return response()->json(['message' => 'Contact not found.'], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/contacts/{id}",
     *     summary="Delete a contact",
     *     tags={"Contacts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Deleted successfully")
     * )
     */
    public function destroy(Request $request, Contact $customer)
    {
        $customer->delete();
        return response()->json(null, 204);
    }
}
