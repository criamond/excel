<?php

namespace App\Http\Controllers;

use App\Exports\FileExport;
use App\Http\Requests\ExcelRequest;
use App\Imports\FileImport;
use App\Services\ArticlesServiceInterface;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Support\Collection;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class ExcelController extends Controller
{

    public function articles(ExcelRequest $request, ArticlesServiceInterface $articles)
    {
        if($request->file('file')) {
            $collection = Excel::toCollection(new FileImport, $request->file('file'));
            $collection=$collection->first();
        } else {
            $collection=$articles->getArticles();
            if(!$collection) {
                $response = [
                    'success' => false,
                    'message' => 'Wrong URL for articles or no JSON',
                ];
                response()->json($response, 400);
            }
        }

        $collection=$collection->map(function($collection)
        {
            $collection['unixtime']=strtotime($collection[2]);
            return $collection;
        });

        $collection=$collection->sortByDesc('unixtime');

        $i=0;
        foreach ($collection as $elem){
            $arr_out[0][$i]=$elem->get(0);
            $arr_out[1][$i]=$elem->get(1);
            $i++;
        }

        $export = new FileExport($arr_out);

        return Excel::download($export, 'output.xlsx');
    }
}

