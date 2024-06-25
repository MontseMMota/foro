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
        // Create a collection of sheets with table data
        $sheets = new SheetCollection([
            'Users' => User::all(),
            'Posts' => Post::all(),
            'Responses' => Response::all(),
            'Likes' => Like::all(),
        ]);

        // Export the sheets to an Excel file
        (new FastExcel($sheets))->export('file.xlsx');

        // Return the file to the browser for download
        return response()->download(public_path('file.xlsx'))->deleteFileAfterSend(true);
    }



}
