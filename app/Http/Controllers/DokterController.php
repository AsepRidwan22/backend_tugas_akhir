<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterStoreRequest;
use App\Http\Requests\RegisterUpdateRequest;
use App\Http\Responses\PrettyJsonResponse;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('id_role', '2')->orderBy('name', 'desc')->get();
            return DataTables::of($data)
                ->removeColumn('email_verified_at')
                ->removeColumn('password')
                ->removeColumn('id_role')
                ->removeColumn('status')
                ->removeColumn('remember_token')
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm btnEdit">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm btnDelete">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.dokter.index');
    }

    public function create()
    {
        return view('admin.dokter.create');
    }

    public function store(RegisterStoreRequest $request): JsonResponse
    {
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_role' => 2
            ]);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Dokter berhasil ditambahkan']);
    }

    public function edit($id): JsonResponse
    {
        try {
            $user = User::where([
                ['id', $id],
                ['id_role', 2]
            ])->first();

            if (!$user) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data dokter tidak ditemukan'], 404);
            }
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data dokter berhasil didapatkan', 'data' => $user->toArray()]);
    }

    public function update(RegisterUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->only(['name', 'email']);
            if ($request->has('password')) {
                $data['password'] = Hash::make($request->get('password'));
            }

            $user = User::where([
                ['id', $id],
                ['id_role', 2]
            ])->first();

            if (!$user) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data user tidak ditemukan'], 404);
            }
            $user->update($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data dokter berhasil diubah']);
    }


    public function destroy($id): JsonResponse
    {
        try {
            $user = User::where([
                ['id', $id],
                ['id_role', 2]
            ])->first();

            if (!$user) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data dokter tidak ditemukan'], 404);
            }
            $user->delete();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data dokter berhasil dihapus']);
    }
}
