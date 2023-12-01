<?php

namespace App\Http\Controllers;

use App\Exports\FileExport;
use App\Http\Requests\ExcelRequest;
use App\Imports\FileImport;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class ExcelController extends Controller
{
    public function import(ExcelRequest $request)
    {
        //$collection = Excel::toCollection(new FileImport, 'D:/users.xlsx');
        $array = Excel::toArray(new FileImport, $request->file('file'));


        $export = new FileExport([
            [1, 2, 3],
            [4, 5, 6]
        ]);

        return Excel::download($export, 'invoices.xlsx');
    }
}

