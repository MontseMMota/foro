<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Response;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        // Validar el archivo
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // max:10240 KB (10MB)
        ]);

        // Obtener el archivo subido
        $file = $request->file('file');

        // Procesar las hojas del archivo Excel
        $sheets = (new FastExcel)->withSheetsNames()->importSheets($file);

        DB::transaction(function() use ($sheets) {
            // dd($sheets);
            // Importar usuarios
            if (isset($sheets['Users'])) {
                foreach ($sheets['Users'] as $line) {
                    
                    $user = User::updateOrCreate(
                        ['id' => $line['id']],
                        [
                            'name' => $line['name'],
                            'email' => $line['email'],
                            'password' => bcrypt($line['password'] ?? 'password'),
                            'singer' => $line['singer'] ?? null,
                            'hobby' => $line['hobby'] ?? null,
                            'role' => $line['role'] ?? 'user',
                        ]
                    );
                }
            }

            // Importar posts
            if (isset($sheets['Posts'])) {
                foreach ($sheets['Posts'] as $line) {
                    $post = Post::updateOrCreate(
                        ['id' => $line['id']],
                        [
                            'user_id' => $line['user_id'],
                            'title' => $line['title'],
                            'content' => $line['content'] ?? '',
                        ]
                    );
                }
              
            }

      
            if (isset($sheets['Responses'])) {
                foreach ($sheets['Responses'] as $line) {
                    // Verificar si 'user_id' está presente y es válido
                    if (isset($line['user_id']) && is_numeric($line['user_id'])) {
                        $user_id = (int) $line['user_id'];
            
                        Response::updateOrCreate(
                            ['id' => $line['id']],
                            [
                                'post_id' => $line['post_id'],
                                'user_id' => $user_id,
                                'content' => $line['content'] ?? '',
                                'like' => $line['like'] ?? false,
                                'updated_at' => $line['updated_at'] ?? now(),
                                'created_at' => $line['created_at'] ?? now(),
                            ]
                        );
                    } else {
                        // Caso donde 'user_id' no está presente o no es válido
                        Response::updateOrCreate(
                          
                            [
                                'id' => $line['id'],
                                'post_id' => $line['post_id'],
                                'content' => $line['content'] ?? '',
                                'like' => $line['like'] ?? false,
                                'updated_at' => $line['updated_at'] ?? now(),
                                'created_at' => $line['created_at'] ?? now(),
                            ]
                        );
                    }
                }
            }

            // Importar likes
            if (isset($sheets['Likes'])) {
                foreach ($sheets['Likes'] as $line) {
                    $like = Like::updateOrCreate(
                        ['id' => $line['id']],
                        [
                            'user_id' => $line['user_id'],
                            'post_id' => $line['post_id'],
                        ]
                    );
                }
            }
        });

        return redirect()->back()->with('success', 'Datos importados exitosamente.');
    }
}