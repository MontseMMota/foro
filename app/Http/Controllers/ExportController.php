<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Response;
use App\Models\Like;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;

class ExportController extends Controller
{
    public function export()
    {
        // Crear una colección de hojas con los datos de las tablas
        $sheets = new SheetCollection([
            'Users' => User::all(),
            'Posts' => Post::all(),
            'Responses' => Response::all(),
            'Likes' => Like::all(),
        ]);

        // Exportar las hojas a un archivo Excel
        (new FastExcel($sheets))->export('file.xlsx');

        // Retornar el archivo al navegador para su descarga
        return response()->download(public_path('file.xlsx'))->deleteFileAfterSend(true);
    }



}
