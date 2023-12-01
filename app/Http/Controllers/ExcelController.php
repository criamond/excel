<?php

namespace App\Http\Controllers;

use App\Exports\FileExport;
use App\Http\Requests\ExcelRequest;
use App\Imports\FileImport;
use Exception;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class ExcelController extends Controller
{
    public function import(ExcelRequest $request)
    {
        if($request->file('file')) {
            $array = Excel::toArray(new FileImport, $request->file('file'));
            $array=$array[0];


        } else {
            try {
                $json = json_decode(file_get_contents(env('URL_articles')));
            } catch (Exception $e){
                $response = [
                    'success' => false,
                    'message' => 'Wrong URL for articles or not JSON',
                ];
                response()->json($response, 400);
            }
            try {
                foreach ($json as $article) {
                    $array[] = [$article->title->rendered, $article->content->rendered,$article->date];
                }
            } catch (Exception $e){
                $response = [
                    'success' => false,
                    'message' => 'Wrong JSON',
                ];
                response()->json($response, 400);
            }

        }


        $res_array=array_map(function($el)
        {
            $el[2]=strtotime($el[2]);
            return $el;
        },$array);

        $r=$res_array;

        uasort($res_array, function($a, $b) {
            if ($a[2] == $b[2]) {
                return 0;
            }
            return ($a[2] > $b[2]) ? -1 : 1;
        });



        $i=0;
        foreach ($res_array as $elem){
            $arr_out[0][$i]=$elem[0];
            $arr_out[1][$i]=$elem[1];
            $i++;
        }

        $export = new FileExport($arr_out);

        return Excel::download($export, 'output.xlsx');
    }
}

