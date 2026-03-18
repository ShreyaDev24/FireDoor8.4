<?php

// app/Http/Controllers/CoreCertificateController.php
namespace App\Http\Controllers;

use App\Models\CoreCertificate;
use App\Models\ConfigurableItems;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CoreCertificateController extends Controller
{
    public function index()
    {
        $certificates = CoreCertificate::join('configurableitems','configurableitems.id','core_certificates.brand_of_core')->where('core_certificates.user_id', Auth::id())->select('core_certificates.*','configurableitems.name')->get();
        return view('core_certificates.index', compact('certificates'));
    }

    public function create()
    {
        $brands = ConfigurableItems::orderBy('orderBy','ASC')->get();

        $certMap = [
            4 => [
                'FD30' => 'FEA/F99112 Revision L',
                'FD60' => 'FEA/F96103  Revision Q',
            ],
            8 => [
                'FD30' => 'BMT/CNA/F15159 Revision F',
                'FD60' => 'WF377027 Revision A',
            ],
            1 => [
                'FD30' => 'Chilt/A02066 Revision P',
                'FD60' => 'Chilt/A02067 Revision M',
            ],
            2 => [
                'FD30' => 'Chilt/A01204 Revision H',
                'FD60' => 'Chilt/A01205 Part 1 Revision K',
            ],
            7 => [
                'FD30' => 'FEA98164 Revision P',
                'FD60' => 'FEA/F02141 Revision M',
            ],
            6 => [
                'FD30' => 'WF399992 Revision E',
                'FD60' => 'WF399992 Revision E',
            ],
            5 => [
                'FD30' => '10133/22-2.R1',
                'FD60' => '10133/22-2.R1',
            ],
        ];

        return view('core_certificates.create', compact('brands','certMap'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_of_core' => 'required|string|max:255',
            'fire_rating' => [
            'required',
                Rule::unique('core_certificates')->where(function ($query) use ($request) {
                    return $query->where('user_id', auth()->id())
                         ->where('brand_of_core', $request->brand_of_core);
                }),
            ],
            'test_certificate_reference' => 'required|string|max:255',
            'expiry_date' => 'nullable|date',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:15360',
        ]);

        $path = null;
        if ($request->hasFile('document')) {
            $filename = time().'_'.$request->file('document')->getClientOriginalName();
            $request->file('document')->move(public_path('certificates'), $filename);

            // Store only relative path
            $path = 'certificates/' . $filename;
        }

        CoreCertificate::create([
            'user_id' => Auth::id(),
            'brand_of_core' => $request->brand_of_core,
            'fire_rating' => $request->fire_rating,
            'test_certificate_reference' => $request->test_certificate_reference,
            'expiry_date' => $request->expiry_date,
            'document_path' => $path,
            'status' => 1,
        ]);

        return redirect()->route('core_certificates.index')->with('success', 'Certificate added successfully');
    }

    public function edit(CoreCertificate $coreCertificate)
    {
        $brands = ConfigurableItems::orderBy('orderBy', 'ASC')->get();
        $certMap = [
            4 => [
                'FD30' => 'FEA/F99112 Revision L',
                'FD60' => 'FEA/F96103  Revision Q',
            ],
            8 => [
                'FD30' => 'BMT/CNA/F15159 Revision F',
                'FD60' => 'WF377027 Revision A',
            ],
            1 => [
                'FD30' => 'Chilt/A02066 Revision P',
                'FD60' => 'Chilt/A02067 Revision M',
            ],
            2 => [
                'FD30' => 'Chilt/A01204 Revision H',
                'FD60' => 'Chilt/A01205 Part 1 Revision K',
            ],
            7 => [
                'FD30' => 'FEA98164 Revision P',
                'FD60' => 'FEA/F02141 Revision M',
            ],
            6 => [
                'FD30' => 'WF399992 Revision E',
                'FD60' => 'WF399992 Revision E',
            ],
            5 => [
                'FD30' => '10133/22-2.R1',
                'FD60' => '10133/22-2.R1',
            ],
        ];
        return view('core_certificates.edit', compact('coreCertificate','brands','certMap'));
    }

    public function update(Request $request, CoreCertificate $coreCertificate)
    {
        $request->validate([
            'brand_of_core' => 'required|string|max:255',
            'fire_rating' => [
                'required',
                Rule::unique('core_certificates')->where(function ($query) use ($request) {
                    return $query->where('user_id', auth()->id())
                                ->where('brand_of_core', $request->brand_of_core);
                })->ignore($coreCertificate->id),
            ],
            'test_certificate_reference' => 'required|string|max:255',
            'expiry_date' => 'nullable|date',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $path = $coreCertificate->document_path; // old path

        if ($request->hasFile('document')) {
            // Pehle ka file delete kar do (agar exist karta ho)
            if ($path && file_exists(public_path($path))) {
                unlink(public_path($path));
            }

            // Naya file upload karo
            $filename = time().'_'.$request->file('document')->getClientOriginalName();
            $request->file('document')->move(public_path('certificates'), $filename);

            // Sirf relative path store karein
            $path = 'certificates/' . $filename;
        }

        $coreCertificate->update([
            'brand_of_core' => $request->brand_of_core,
            'fire_rating' => $request->fire_rating,
            'test_certificate_reference' => $request->test_certificate_reference,
            'expiry_date' => $request->expiry_date,
            'document_path' => $path,
        ]);

        return redirect()->route('core_certificates.index')->with('success', 'Certificate updated successfully');
    }

    public function destroy(CoreCertificate $coreCertificate)
    {
        if ($coreCertificate->document_path) {
            Storage::disk('public')->delete($coreCertificate->document_path);
        }
        $coreCertificate->delete();
        return redirect()->route('core_certificates.index')->with('success', 'Certificate deleted successfully');
    }
}
