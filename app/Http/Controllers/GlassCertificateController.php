<?php

namespace App\Http\Controllers;

use App\Models\GlassCertificate;
use App\Models\ConfigurableItems;
use App\Models\GlassType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlassCertificateController extends Controller
{
    public function index()
    {
        $certificates = GlassCertificate::with(['glassType', 'user'])
            ->latest()
            ->paginate(10);

        return view('glass_certificates.index', compact('certificates'));
    }

    public function create()
    {
        $glassTypes = GlassType::where('status', 1)->get();
        $brands = ConfigurableItems::orderBy('orderBy','ASC')->get();

        return view('glass_certificates.create', compact('glassTypes','brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'glass_type_id' => 'required|exists:glass_type,id',
            'certificate_reference' => 'required|string|max:255',
            'expiry_date' => 'nullable|date',
        ]);

        GlassCertificate::create([
            'user_id' => Auth::id(),
            'glass_type_id' => $request->glass_type_id,
            'brand_of_core' => $request->brand_of_core,
            'fire_rating' => $request->fire_rating,
            'certificate_reference' => $request->certificate_reference,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('glass-certificates.index')
            ->with('success', 'Certificate created successfully');
    }

    public function edit(GlassCertificate $glassCertificate)
    {
        $glassTypes = GlassType::where('status', 1)->get();

        return view('glass_certificates.edit', compact('glassCertificate', 'glassTypes'));
    }

    public function update(Request $request, GlassCertificate $glassCertificate)
    {
        $request->validate([
            'glass_type_id' => 'required|exists:glass_type,id',
            'certificate_reference' => 'required|string|max:255',
            'expiry_date' => 'nullable|date',
        ]);

        $glassCertificate->update($request->all());

        return redirect()->route('glass-certificates.index')
            ->with('success', 'Certificate updated successfully');
    }

    public function destroy(GlassCertificate $glassCertificate)
    {
        $glassCertificate->delete();

        return redirect()->route('glass-certificates.index')
            ->with('success', 'Certificate deleted successfully');
    }

    public function getByBrand(Request $request)
    {
        $brandId    = $request->brand_id;   // 1,2,3
        $fireRating = $request->fire_rating; // NFR, FD30, FD60

        $query = GlassType::query();

        // 🔹 Brand mapping (adjust column names if needed)
        if ($brandId == 1) {
            $query->whereNotNull('Streboard');
        } elseif ($brandId == 2) {
            $query->whereNotNull('Halspan');
        } elseif ($brandId == 4) {
            $query->whereNotNull('VicaimaDoorCore');
        } elseif ($brandId == 5) {
            $query->whereNotNull('Seadec');
        } elseif ($brandId == 6) {
            $query->whereNotNull('Deanta');
        } elseif ($brandId == 7) {
            $query->whereNotNull('Flamebreak');
        } elseif ($brandId == 8) {
            $query->whereNotNull('Stredor');
        } elseif ($brandId == 9) {
            $query->whereNotNull('MMM');
        }

        // 🔹 Fire Rating filter
        if ($fireRating === 'NFR') {
            $query->where('NFR', 'NFR');
        } elseif ($fireRating === 'FD30') {
            $query->whereNotNull('FD30');
        } elseif ($fireRating === 'FD60') {
            $query->whereNotNull('FD60');
        }

        $glassTypes = $query
            ->Join('selected_glass_type', function($join): void {
                    $join->on('glass_type.id', '=', 'selected_glass_type.glass_id');
                })
            ->select('glass_type.id', 'glass_type.GlassType', 'glass_type.GlassThickness')
            ->where('selected_glass_type.editBy',Auth::user()->id)
            ->orderBy('glass_type.GlassType')
            ->get();

        return response()->json($glassTypes);
    }
}

