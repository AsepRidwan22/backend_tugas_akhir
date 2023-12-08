<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Responses\PrettyJsonResponse;
use App\Models\Category;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PostController extends Controller
{
    function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Post::where('id_user', auth()->user()->id)->get();
            return DataTables::of($data)
                ->removeColumn('id_user')
                ->removeColumn('id_category')
                ->removeColumn('excerpt')
                ->removeColumn('body')
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->removeColumn('published_at')
                ->addIndexColumn()
                ->addColumn('category', function (Post $post) {
                    return $post->category->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm btnEdit">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm btnDelete">Delete</a>';
                    return $btn;
                })
                ->editColumn('image', function (Post $post) {
                    return '<img src="' . asset('storage/' . $post->image) . '" width="100px" height="auto" />';
                })
                ->editColumn('slug', function (Post $post) {
                    return '<a href="' . $post->slug . '">' . $post->slug . '</a>';
                })
                ->rawColumns(['action', 'slug', 'image'])
                ->make(true);
        }
        return view('admin.posts.index');
    }

    public function store(StorePostRequest $request)
    {
        try {
            $data = $request->all();

            if ($request->file('image')) {
                $data['image'] = $request->file('image')->storeAs(
                    'assets/img-posts',
                    'posts_' . time() . '.' . $request->file('image')->extension(),
                    'public'
                );
            }

            $data['id_user'] = auth()->user()->id;
            $data['excerpt'] = Str::limit(strip_tags($request->body), 100, '...');

            Post::create($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Postingan berhasil ditambahkan']);
    }

    // public function show(Post $post)
    // {
    //     return view('dashboard.posts.show', [
    //         'post' => $post
    //     ]);
    // }

    public function edit($id)
    {
        try {
            $post = Post::where('id', $id)->first();

            if (!$post) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data post tidak ditemukan'], 404);
            }
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data post berhasil didapatkan', 'data' => $post->toArray()]);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        try {
            $post = Post::where('id', $id)->first();

            if (!$post) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data user tidak ditemukan'], 404);
            }

            $data = $request->all();
            if ($request->file('image')) {
                Storage::delete($post->image);
                $data['image'] = $request->file('image')->storeAs(
                    'assets/img-posts',
                    'posts_' . time() . '.' . $request->file('image')->extension(),
                    'public'
                );
            }
            $data['id_user'] = auth()->user()->id;
            $data['excerpt'] = Str::limit(strip_tags($request->body), 100, '...');
            $post->update($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data posts berhasil diubah']);
    }

    public function destroy($id)
    {
        try {
            $post = Post::where('id', $id)->first();
            if (!$post) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data post tidak ditemukan'], 404);
            }
            $post->delete();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data post berhasil dihapus']);
    }

    public function checkSlug(Request $request)
    {
        try {
            $slug = Str::of(request('title'))->slug()->value;
            while (true) {
                $post = Post::query()->where('slug', '=', $slug)->get();
                if ($post->isNotEmpty()) {
                    $slug .= '-' . Str::lower(Str::random(5));
                    continue;
                } else {
                    break;
                }
            }
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'slug'  => $slug], 200);
    }
}
