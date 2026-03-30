<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\SelectedOption;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use DB;

class FinishCostController extends Controller
{

    public function index()
    {
        $auth = auth()->user();

        $items = Option::leftJoin('selected_option as so', function ($join) use ($auth) {

            $join->on('options.id', '=', 'so.optionId')
                ->where('so.SelectedUserId', $auth->id);

        })
        ->whereIn('options.editBy', [$auth->id, 1])
        ->whereIn('options.OptionSlug', ['Architrave_Finish', 'Frame_Finish', 'Door_Leaf_Facing', 'door_leaf_finish'])
        ->where(['options.is_deleted' => 0])
        ->select(
            'options.*',
            'so.id as selectedId',
            'so.SelectedOptionCost'
        )
        ->whereNotIn('options.configurableitems',[3])
        ->orderBy('options.configurableitems')
        ->orderBy('options.OptionName')
        ->orderBy('options.OptionValue')
        ->get();

        return view('SelectedOptions.finish_cost.index', compact('items','auth'));
    }


    public function updateSelected(Request $request)
    {
        $userId = auth()->id();

        $keys = collect($request->rows)
            ->where('checked', true)
            ->pluck('id')
            ->toArray();

        if (!empty($keys)) {

            SelectedOption::where('SelectedUserId', $userId)
                ->whereNotIn('optionId', $keys)
                ->delete();

            foreach ($request->rows as $row) {

            $electedOption = Option::Where('id', $row['id'])->select('id', 'OptionSlug', 'OptionValue', 'UnderAttribute', 'OptionKey','configurableitems')->first();

                if ($row['checked']) {

                    SelectedOption::updateOrCreate(

                        [
                            'SelectedUserId' => $userId,
                            'optionId' => $row['id']
                        ],

                        [
                            'SelectedOptionCost' => $row['price'] ?? 0,
                            'configurableitems' => $electedOption->configurableitems,
                            'tag' => $electedOption->OptionSlug,
                            'SelectedUnderAttribute' => $electedOption->UnderAttribute,
                            'SelectedOptionSlug' => $electedOption->OptionSlug,
                            'SelectedOptionKey' => $electedOption->OptionKey,
                            'SelectedOptionValue' => $electedOption->OptionValue,
                        ]

                    );
                }
            }

        } else {

            SelectedOption::where('SelectedUserId', $userId)->delete();

        }

        return response()->json(['status' => 'ok']);
    }


    public function exportSelected(Request $request)
    {
        $ids = $request->ids;

        $auth = auth()->user();

        $items = Option::leftJoin('selected_option as so', function ($join) use ($auth) {

            $join->on('options.id', '=', 'so.optionId')
                ->where('so.SelectedUserId', $auth->id);

        })
        ->whereIn('options.editBy', [$auth->id, 1])
        ->whereIn('options.id', $ids)
        ->whereIn('options.OptionSlug', ['Architrave_Finish', 'Frame_Finish', 'Door_Leaf_Facing', 'door_leaf_finish'])
        ->where(['options.is_deleted' => 0])
        ->select(
            'options.*',
            'so.id as selectedId',
            'so.SelectedOptionCost'
        )
        ->whereNotIn('options.configurableitems',[3])
        ->orderBy('options.OptionSlug')
        ->orderBy('options.configurableitems')
        ->orderBy('options.OptionName')
        ->orderBy('options.OptionValue')
        ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = [
            'Configurable Items',
            'Door Leaf Name',
            'Under Attribute',
            'Door Leaf',
            'Price'
        ];

        $sheet->fromArray($headers, NULL, 'A1');

        // Bold header
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(15);

        $row = 2;

        foreach ($items as $item) {

            $sheet->setCellValue('A'.$row, configurationDoor($item->configurableitems));
            $sheet->setCellValue('B'.$row, str_replace('_',' ',$item->OptionSlug));
            $sheet->setCellValue('C'.$row, str_replace('_',' ',$item->UnderAttribute));
            $sheet->setCellValue('D'.$row, $item->OptionValue);
            $sheet->setCellValue('E'.$row, number_format($item->SelectedOptionCost ?? 0, 2));

            $row++;
        }

        $lastRow = $row - 1;

        // Border
        $sheet->getStyle('A1:E'.$lastRow)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);

        $filename = 'Options_selected_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

}
