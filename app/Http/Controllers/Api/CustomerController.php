<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


/**
 * @OA\Info(
 *     title="Noppal CRM API",
 *     version="1.0.0",
 *     description="API for managing customers in NOPPAL CRM.",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your JWT token in the format 'Bearer {token}'"
 * )
 */

class CustomerController extends Controller
{

/**
     * @OA\Get(
     *     path="/api/customers",
     *     tags={"Customers"},
     *     summary="List all customers",
     *     description="Retrieve a list of all customers for the authenticated user.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of customers",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-30T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T12:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        return Customer::all();
    }

   /*  public function store(Request $request)
    {
        // Extrahiere die rows aus dem Request
        $data = $request->input('rows', []);

        // Validierung der Eingabe: rows muss ein Array sein
        if (!is_array($data)) {
            return response()->json(['error' => 'Rows must be an array'], 400);
        }

        $res = ['success' => [], 'errors' => []];

        // Verarbeite jede Row in einer Transaktion
        foreach ($data as $index => $row) {
            // Validierungsregeln für einen einzelnen Kunden
            $validator = Validator::make($row, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                #'resources' => 'sometimes|array',
                #'resources.*.name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                $res['errors'][] = [
                    'index' => $index,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'data' => $row,
                ];
                continue;
            }

            try {
                // Erstelle Kunden und Ressourcen in einer Transaktion
                $customer = DB::transaction(function () use ($row, $request) {
                    $customer = $request->user()->customers()->create([
                        'name' => $row['name'],
                        'name' => $row['phone'],
                        'email' => $row['email'],
                        #'user_id' => $request->user()->id,
                    ]);

                    // Erstelle abhängige Ressourcen, falls vorhanden
                    if (isset($row['resources']) && is_array($row['resources'])) {
                        $resources = collect($row['resources'])->map(function ($resource) use ($customer, $request) {
                            return [
                                'name' => $resource['name'],
                                'customer_id' => $customer->id,
                                'user_id' => $request->user()->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        });

                        $customer->resources()->insert($resources->toArray());
                    }

                    $customer->load('resources');
                    return $customer;
                });

                $res['success'][] = [
                    'id' => $customer->id,
                    'message' => 'Customer created successfully',
                    'data' => new CustomerResource($customer),
                ];
            } catch (\Exception $e) {
                $res['errors'][] = [
                    'index' => $index,
                    'message' => $e->getMessage(),
                    'data' => $row,
                ];
            }
        }

        // Bestimme den Statuscode
        $status = count($res['errors']) > 0 ? 400 : 201;
        if (count($res['success']) > 0 && count($res['errors']) > 0) {
            $status = 207; // HTTP 207 Multi-Status für teilweise Erfolge
        }

        return response()->json($res, $status);
    } */


/**
 * @OA\Post(
 *     path="/api/customers",
 *     tags={"Customers"},
 *     summary="Create multiple customers",
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             required={"rows"},
 *             @OA\Property(
 *                 property="rows",
 *                 type="array",
 *                 @OA\Items(
 *                     required={"name", "email"},
 *                     @OA\Property(property="name", type="string"),
 *                     @OA\Property(property="email", type="string", format="email")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(response=201, description="All customers created"),
 *     @OA\Response(response=207, description="Partial success"),
 *     @OA\Response(response=400, description="Validation or creation failed")
 * )
 */

    public function store(Request $request)
    {
        // Extrahiere die rows aus dem Request
        $data = $request->input('rows', []);

        // Validierung: rows muss ein Array sein
        if (!is_array($data)) {
            return response()->json(['error' => 'Rows must be an array'], 400);
        }

        $res = ['success' => [], 'errors' => []];

        // Verarbeite jede Row
        foreach ($data as $index => $row) {
            // Validierungsregeln für einen einzelnen Customer
            $validator = Validator::make($row, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:fil_customers,email',
            ]);

            if ($validator->fails()) {
                $res['errors'][] = [
                    'index' => $index,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'data' => $row,
                ];
                continue;
            }

            try {
                // Erstelle Customer in einer Transaktion
                $customer = DB::transaction(function () use ($row, $request) {
                    return $request->user()->customers()->create([
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'user_id' => $request->user()->id,
                    ]);
                });

                $res['success'][] = [
                    'id' => $customer->id,
                    'message' => 'Customer created successfully',
                    'data' => $customer,
                ];
            } catch (\Exception $e) {
                $res['errors'][] = [
                    'index' => $index,
                    'message' => $e->getMessage(),
                    'data' => $row,
                ];
            }
        }

        // Bestimme den Statuscode
        $status = count($res['errors']) > 0 ? 400 : 201;
        if (count($res['success']) > 0 && count($res['errors']) > 0) {
            $status = 207; // HTTP 207 Multi-Status für teilweise Erfolge
        }

        return response()->json($res, $status);
    }

    /**
     * @OA\Get(
     *     path="/api/customers/{id}",
     *     tags={"Customers"},
     *     summary="Find customer by ID",
     *     description="Retrieve a customer by their ID.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-30T12:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T12:00:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Customer not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show(Request $request, Customer $customer)
    {
        if ($customer->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $customer;
    }

    /**
     * @OA\Put(
     *     path="/api/customers/{id}",
     *     tags={"Customers"},
     *     summary="Update an existing customer",
     *     description="Update a customers details by their ID.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="John Updated"),
     *             @OA\Property(property="email", type="string", format="email", example="john.updated@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Updated"),
     *             @OA\Property(property="email", type="string", example="john.updated@example.com"),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-30T12:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T12:00:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Customer not found"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation failed")
     * )
     */
    public function update(Request $request, Customer $customer)
    {
        if ($customer->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:fil_customers,email,' . $customer->id,
        ]);

        $customer->update($validated);
        return $customer;
    }

    /**
     * @OA\Delete(
     *     path="/api/customers/{id}",
     *     tags={"Customers"},
     *     summary="Delete a customer",
     *     description="Delete a customer by their ID.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Customer deleted"),
     *     @OA\Response(response=404, description="Customer not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy(Request $request, Customer $customer)
    {
        if ($customer->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $customer->delete();
        return response()->json(null, 204);
    }


}
