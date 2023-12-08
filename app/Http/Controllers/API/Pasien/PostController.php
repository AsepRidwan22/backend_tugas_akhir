<?php

namespace App\Http\Controllers\Api\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Responses\PrettyJsonResponse;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10); // Jumlah postingan per halaman, default 10

            $query = Post::with("category")->latest();

            // if ($request->has('search')) {
            //     $query->where('title', 'like', '%' . $request->search . '%');
            // }

            if ($request->has('category')) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            }

            // if ($request->has('author')) {
            //     $query->whereHas('author', function ($q) use ($request) {
            //         $q->where('username', $request->author);
            //     });
            // }

            $posts = $query->paginate($perPage);

            // $posts = Post::paginate($perPage);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $posts]);
    }

    // Fungsi untuk membuat paginator secara manual
    protected function paginate($items, $perPage = 15, $currentPage = null, $options = [])
    {
        $currentPage = $currentPage ?: Paginator::resolveCurrentPage();

        $items = $items instanceof Collection ? $items : Collection::make($items);

        $paginator = new LengthAwarePaginator($items->forPage($currentPage, $perPage), $items->count(), $perPage, $currentPage, $options);

        return $paginator->withQueryString();
    }

    public function show(Post $post)
    {
        try {
            $data = [
                'active' => 'posts',
                'title' => $post->title,
                'post' => $post,
            ];
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $data]);
    }
}
