<?php

namespace App\Modules\Tenants\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tenants\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();
        return response()->json($tenants);
    }

    public function show(int $id)
    {
        $tenant = Tenant::findOrFail($id);
        return response()->json($tenant);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $tenant = Tenant::create($request->all());
        return response()->json($tenant, 201);
    }

    public function update(Request $request, int $id)
    {
        $tenant = Tenant::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $tenant->update($request->all());
        return response()->json($tenant);
    }

    public function destroy(int $id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete();
        return response()->json(null, 204);
    }

    public function getTeams(int $id)
    {
        $tenant = Tenant::findOrFail($id);
        return response()->json($tenant->teams);
    }

    public function getUsers(int $id)
    {
        $tenant = Tenant::findOrFail($id);
        return response()->json($tenant->users);
    }

    public function getBookings(int $id)
    {
        $tenant = Tenant::findOrFail($id);
        return response()->json($tenant->bookings);
    }

    public function updateSettings(Request $request, int $id)
    {
        $tenant = Tenant::findOrFail($id);
        
        $request->validate([
            'settings' => 'required|array'
        ]);

        $tenant->settings = $request->settings;
        $tenant->save();

        return response()->json($tenant);
    }
} 