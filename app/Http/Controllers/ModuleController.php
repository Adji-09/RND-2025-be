<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;

use App\Models\Module;

use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function getAll()
    {
        $menus = Module::orderBy('module_position', 'ASC')->get();

        if ($menus->count()) {
            foreach ($menus as $row)
            {
                $hari = date('l', strtotime($row->created_at));
                $tanggal = substr($row->created_at, 8, 2);
                $bulan = substr($row->created_at, 5, 2);
                $tahun = substr($row->created_at, 0, 4);
                $jam = substr($row->created_at, 11, 8);

                $datas[] = array(
                    'module_id' => $row->module_id,
                    'module_name' => $row->module_name,
                    'module_icon' => $row->module_icon,
                    'module_url' => $row->module_url,
                    'module_parent' => $row->module_parent,
                    'module_position' => $row->module_position,
                    'module_status' => $row->module_status,
                    'module_nav' => $row->module_nav,
                    'is_superadmin' => $row->is_superadmin,
                    'created_at_ori' => $row->created_at,
                    'created_at' => Helper::dayName($hari).", ".$tanggal." ".Helper::monthName($bulan)." ".$tahun." ".$jam,
                    'updated_at' => $row->updated_at
                );
            }

            return response()->json([
                'status' => true,
                'data' => $datas
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'draw' => 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Data not found!'
            ], 200);
        }
    }

    public function getById($id)
    {
        $menus = Module::orderBy('module_position', 'ASC')
            ->where('module_id', $id)
            ->get();

        if ($menus->count()) {
            foreach ($menus as $row)
            {
                $hari = date('l', strtotime($row->created_at));
                $tanggal = substr($row->created_at, 8, 2);
                $bulan = substr($row->created_at, 5, 2);
                $tahun = substr($row->created_at, 0, 4);
                $jam = substr($row->created_at, 11, 8);

                $datas[] = array(
                    'module_id' => $row->module_id,
                    'module_name' => $row->module_name,
                    'module_icon' => $row->module_icon,
                    'module_url' => $row->module_url,
                    'module_parent' => $row->module_parent,
                    'module_position' => $row->module_position,
                    'module_status' => $row->module_status,
                    'module_nav' => $row->module_nav,
                    'is_superadmin' => $row->is_superadmin,
                    'created_at_ori' => $row->created_at,
                    'created_at' => Helper::dayName($hari).", ".$tanggal." ".Helper::monthName($bulan)." ".$tahun." ".$jam,
                    'updated_at' => $row->updated_at
                );
            }

            return response()->json([
                'status' => true,
                'data' => $datas
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Data not found!'
            ], 200);
        }
    }

    public function update(Request $request)
    {
        $user = Module::find($request->id);

        $update = $user->update([
            'is_superadmin' => $request->is_superadmin,
            'module_status' => $request->module_status
        ]);

        if ($update) {
            return response()->json([
                'status' => true,
                'message' => 'Change module permissions successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Change module permissions failed!'
            ], 400);
        }
    }

    public function update_pos(Request $request)
    {
        $module_id = Module::find($request->module_id);

        $update = $module_id->update([
            'module_position' => $request->module_position
        ]);

        if ($update) {
            return response()->json([
                'status' => true,
                'message' => 'Change module order successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Change module order failed!'
            ], 400);
        }
    }
}
