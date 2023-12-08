<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Http\Responses\PrettyJsonResponse;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;


class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::all();
            return DataTables::of($data)
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm btnEdit">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm btnDelete">Delete</a>';
                    return $btn;
                })
                ->editColumn('slug', function (Category $category) {
                    return '<a href="' . $category->slug . '">' . $category->slug . '</a>';
                })
                ->rawColumns(['action', 'slug'])
                ->make(true);
        }
        return view('admin.categories.index');
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            Category::create($request->all());
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Category berhasil ditambahkan']);
    }

    public function edit($id): JsonResponse
    {
        try {
            $category = Category::where('id', ':id')->setBindings(['id' => $id])->first();

            if (!$category) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data category tidak ditemukan'], 404);
            }
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data category berhasil didapatkan', 'data' => $category->toArray()]);
    }

    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        try {
            $category = Category::where('id', ':id')->setBindings(['id' => $id])->first();

            if (!$category) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data user tidak ditemukan'], 404);
            }
            $category->update($request->all());
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data category berhasil diubah']);
    }


    public function destroy($id): JsonResponse
    {
        try {
            $category = Category::where('id', ':id')->setBindings(['id' => $id])->first();
            if (!$category) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data category tidak ditemukan'], 404);
            }
            $category->delete();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data category berhasil dihapus']);
    }

    public function checkSlug(Request $request)
    {
        try {
            $slug = Str::of(request('name'))->slug()->value;
            while (true) {
                $category = Category::query()->where('slug', '=', $slug)->get();
                if ($category->isNotEmpty()) {
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
