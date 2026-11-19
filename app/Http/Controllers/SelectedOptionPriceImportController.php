<?php

namespace App\Http\Controllers;

use App\Imports\SelectedOptionPriceImport;
use App\Support\SelectedOptionModuleRegistry;
use Illuminate\Http\Request;

class SelectedOptionPriceImportController extends Controller
{
    public function import(Request $request)
    {
        $validated = $request->validate([
            'module' => ['required', 'string'],
            'file' => ['required', 'file', 'max:5120'],
        ], [
            'file.required' => 'Choose an Excel file to import.',
            'file.max' => 'The Excel file must be 5 MB or smaller.',
        ]);

        $extension = strtolower($request->file('file')->getClientOriginalExtension());

        if (!in_array($extension, ['xlsx', 'xls'], true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only Excel files (.xlsx, .xls) can be imported.',
                'processed' => 0,
                'updated' => 0,
                'skipped' => 0,
                'failed' => 1,
                'errors' => [[
                    'row' => 0,
                    'reason' => 'Only Excel files (.xlsx, .xls) can be imported.',
                ]],
            ], 422);
        }

        if (!SelectedOptionModuleRegistry::has($validated['module'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unknown selected option module.',
                'processed' => 0,
                'updated' => 0,
                'skipped' => 0,
                'failed' => 1,
                'errors' => [[
                    'row' => 0,
                    'reason' => 'Unknown selected option module.',
                ]],
            ], 422);
        }

        $result = (new SelectedOptionPriceImport())->import(
            $request->file('file')->getRealPath(),
            $validated['module'],
            (int) auth()->id()
        );

        return response()->json($result, $result['status'] === 'ok' ? 200 : 422);
    }
}
