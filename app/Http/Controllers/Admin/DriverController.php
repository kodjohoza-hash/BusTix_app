<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Bus;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::with('bus')->latest()->paginate(10);
        $buses   = Bus::all();
        return view('admin.drivers', compact('drivers', 'buses'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name'           => 'required|string|max:100',
        'surname'        => 'required|string|max:100',
        'telephone'      => 'required|string|max:20',
        'license_number' => 'required|string|unique:drivers,license_number',
        'bus_id'         => 'nullable|exists:buses,id',
    ]);

    Driver::create($request->only(['name','surname','telephone','license_number','bus_id']));
    return redirect()->route('admin.drivers')->with('success', 'Driver added successfully!');
}

public function update(Request $request, string $id)
{
    $driver = Driver::findOrFail($id);

    $request->validate([
        'name'           => 'required|string|max:100',
        'surname'        => 'required|string|max:100',
        'telephone'      => 'required|string|max:20',
        'license_number' => 'required|string|unique:drivers,license_number,' . $id,
        'bus_id'         => 'nullable|exists:buses,id',
    ]);

    $driver->update($request->only(['name','surname','telephone','license_number','bus_id']));
    return redirect()->route('admin.drivers')->with('success', 'Driver updated successfully!');
}

    public function destroy(string $id)
    {
        Driver::findOrFail($id)->delete();
        return redirect()->route('admin.drivers')->with('success', 'Driver deleted successfully!');
    }
    public function checkLicense(Request $request)
{
    $exists = \App\Models\Driver::where('license_number', $request->license)
        ->when($request->exclude_id, fn($q) => $q->where('id', '!=', $request->exclude_id))
        ->first();

    return response()->json([
        'taken' => $exists ? true : false,
        'name'  => $exists ? $exists->name . ' ' . $exists->surname : null,
    ]);
}
}