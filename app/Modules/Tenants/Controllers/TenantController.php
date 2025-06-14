<?php

namespace App\Modules\Tenants\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tenants\Models\Tenant;
use App\Modules\Tenants\Requests\StoreTenantRequest;
use App\Modules\Tenants\Resources\TenantResource;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('bookings')->get();
        return TenantResource::collection($tenants);
    }

    public function show(int $id)
    {
        $tenant = Tenant::with('bookings')->findOrFail($id);
        return new TenantResource($tenant);
    }

    public function store(StoreTenantRequest $request)
    {
        $tenant = Tenant::create($request->validated());
        return new TenantResource($tenant);
    }

    public function update(Request $request, int $id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->update($request->all());
        return new TenantResource($tenant);
    }

    public function destroy(int $id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete();
        return response()->json(null, 204);
    }

    public function getActiveTenants()
    {
        $tenants = Tenant::where('is_active', true)
            ->with('bookings')
            ->get();
        return TenantResource::collection($tenants);
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