<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class ResourceController extends Controller
{
    public function index()
    {
        return Customer::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Weitere Validierungen
        ]);

        $resource = Resource::create($validated);
        return response()->json($resource, 201);
    }

    // Weitere CRUD Methoden...
}
