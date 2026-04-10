<?php

namespace App\Http\Controllers;

use App\Models\GlassCertificate;
use App\Models\ConfigurableItems;
use App\Models\GlassType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class GlassCertificateController extends Controller
{
    public function index()
    {
        $auth = auth()->user();

        $certificates = GlassCertificate::with(['glassType', 'user'])
            ->whereIn('user_id', [$auth->id, 1])
            ->get();

        return view('glass_certificates.index', compact('certificates'));
    }

    public function create()
    {
        $glassTypes = GlassType::where('status', 1)->get();
        $brands = ConfigurableItems::orderBy('orderBy','ASC')->get();

        $certMap = [
            4 => [
                'NFR' => 'FEA/F99112 Revision L',
                'FD30' => 'FEA/F99112 Revision L',
                'FD60' => 'FEA/F96103  Revision Q',
            ],
            8 => [
                'NFR' => 'BMT/CNA/F15159 Revision F',
                'FD30' => 'BMT/CNA/F15159 Revision F',
                'FD60' => 'WF377027 Revision A',
            ],
            1 => [
                'NFR' => 'Chilt/A02066 Revision P',
                'FD30' => 'Chilt/A02066 Revision P',
                'FD60' => 'Chilt/A02067 Revision M',
            ],
            2 => [
                'NFR' => 'Chilt/A01204 Revision H',
                'FD30' => 'Chilt/A01204 Revision H',
                'FD60' => 'Chilt/A01205 Part 1 Revision K',
            ],
            7 => [
                'NFR' => 'FEA98164 Revision P',
                'FD30' => 'FEA98164 Revision P',
                'FD60' => 'FEA/F02141 Revision M',
            ],
            6 => [
                'NFR' => 'WF399992 Revision E',
                'FD30' => 'WF399992 Revision E',
                'FD60' => 'WF399992 Revision E',
            ],
            5 => [
                'NFR' => '10133/22-2.R1',
                'FD30' => '10133/22-2.R1',
                'FD60' => '10133/22-2.R1',
            ],
        ];


        return view('glass_certificates.create', compact('glassTypes','brands','certMap'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_of_core' => 'required|integer',
            'fire_rating' => 'required',
            'glass_thickness' => 'required',
            'glass_type_id' => 'required|exists:glass_type,id',
            'expiry_date' => 'nullable|date',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $path = null;

        if ($request->hasFile('document')) {
            $filename = time().'_'.$request->file('document')->getClientOriginalName();
            $request->file('document')->move(public_path('glassCertificates'), $filename);
            $path = 'glassCertificates/'.$filename;
        }

        GlassCertificate::create([
            'user_id' => auth()->id(),
            'glass_type_id' => $request->glass_type_id,
            'glass_thickness' => $request->glass_thickness,
            'brand_of_core' => $request->brand_of_core,
            'fire_rating' => $request->fire_rating,
            'certificate_reference' => $request->certificate_reference,
            'expiry_date' => $request->expiry_date,
            'document_path' => $path,
        ]);

        return redirect()
            ->route('glass-certificates.index')
            ->with('success', 'Certificate created successfully');
    }

    public function edit(GlassCertificate $glassCertificate)
    {
        $glassTypes = GlassType::where('status', 1)->get();
        $brands = ConfigurableItems::orderBy('orderBy','ASC')->get();
        $certMap = [
            4 => [
                'NFR' => 'FEA/F99112 Revision L',
                'FD30' => 'FEA/F99112 Revision L',
                'FD60' => 'FEA/F96103  Revision Q',
            ],
            8 => [
                'NFR' => 'BMT/CNA/F15159 Revision F',
                'FD30' => 'BMT/CNA/F15159 Revision F',
                'FD60' => 'WF377027 Revision A',
            ],
            1 => [
                'NFR' => 'Chilt/A02066 Revision P',
                'FD30' => 'Chilt/A02066 Revision P',
                'FD60' => 'Chilt/A02067 Revision M',
            ],
            2 => [
                'NFR' => 'Chilt/A01204 Revision H',
                'FD30' => 'Chilt/A01204 Revision H',
                'FD60' => 'Chilt/A01205 Part 1 Revision K',
            ],
            7 => [
                'NFR' => 'FEA98164 Revision P',
                'FD30' => 'FEA98164 Revision P',
                'FD60' => 'FEA/F02141 Revision M',
            ],
            6 => [
                'NFR' => 'WF399992 Revision E',
                'FD30' => 'WF399992 Revision E',
                'FD60' => 'WF399992 Revision E',
            ],
            5 => [
                'NFR' => '10133/22-2.R1',
                'FD30' => '10133/22-2.R1',
                'FD60' => '10133/22-2.R1',
            ],
        ];

        return view('glass_certificates.edit', compact('glassCertificate', 'glassTypes', 'brands', 'certMap'));
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

        $auth = auth()->user();

        $glassTypes = $query->leftJoin('selected_glass_type as slt', function ($join) use ($auth) {
                $join->on('glass_type.id', '=', 'slt.glass_id')
                    ->where('slt.editBy', $auth->id);   // use userId (recommended)
            })
            ->whereIn('glass_type.EditBy', [$auth->id, 1])
            ->select('glass_type.id', 'glass_type.GlassType', 'glass_type.GlassThickness')
            ->orderBy('glass_type.GlassType')
            ->get();

        return response()->json($glassTypes);
    }
}

