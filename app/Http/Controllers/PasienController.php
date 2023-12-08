<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusRequest;
use App\Http\Responses\PrettyJsonResponse;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PasienController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('id_role', '3')->orderBy('name', 'desc')->get();
            return DataTables::of($data)
                ->removeColumn('email_verified_at')
                ->removeColumn('password')
                ->removeColumn('id_role')
                ->removeColumn('remember_token')
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    $label = $row->status == 1 ? 'checked' : '';
                    return "
                    <div class='form-check form-switch form-check-inline form-switch-success'>
                    <input class='toggle-class form-check-input' type='checkbox' data-id='{$row->id}' role='switch' id='form-switch-success' {$label}>
                    <label class='form-check-label' for='form-switch-success'>Active</label>
                    </div>";
                })
                ->addColumn('action', function ($row) {
                    $btn = "<a href='javascript:void(0)' data-toggle='tooltip'  data-id='{$row->id}' data-original-title='Delete' class='btn btn-danger btn-sm btnDelete'>Delete</a>";
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('admin.pasien.index');
    }

    public function changeStatus(StatusRequest $request)
    {
        try {
            $user = User::where([
                ['id', $request->id],
                ['id_role', 3]
            ])->first();

            if (!$user) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data user tidak ditemukan'], 404);
            }

            $user->status = $request->status;
            $user->save();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Status berhasil diubah']);
    }

    public function destroy($id)
    {
        try {
            $user = User::where([
                ['id', $id],
                ['id_role', 3]
            ])->get();

            if (!$user) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data pasien tidak ditemukan'], 404);
            }
            $user->delete();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data pasien berhasil dihapus']);
    }
}
