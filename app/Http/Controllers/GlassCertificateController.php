<?php

namespace App\Http\Controllers;

use App\Models\GlassCertificate;
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

        return view('glass_certificates.create', compact('glassTypes'));
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
}

