<?php

namespace App\Http\Controllers;

use App\Exports\FileExport;
use App\Http\Requests\ExcelRequest;
use App\Imports\FileImport;
use App\Services\ArticlesServiceInterface;
use App\Services\CollectionProcessing;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class ExcelController extends Controller
{

    public function articles(ExcelRequest $request, ArticlesServiceInterface $articles, CollectionProcessing $col_processing)
    {
        if($request->file('file')) {
            $collection = Excel::toCollection(new FileImport, $request->file('file'));
            $collection=$collection->first();
        } else {
            $collection=$articles->getArticles();
            if($collection->isEmpty()) {
                $response = [
                    'success' => false,
                    'message' => 'Wrong URL for articles or no JSON',
                ];
                return response()->json($response, 400);
            }
        }

        $arr_out=$col_processing->setCollection($collection)->process();

        $export = new FileExport($arr_out);

        return Excel::download($export, 'output.xlsx');
    }
}

