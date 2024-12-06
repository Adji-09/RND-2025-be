<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;

use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function getAll()
    {
        $users = User::select('users.*', 'users_role.role AS role_name')
                ->orderBy('created_at', 'desc')
                ->leftJoin('users_role', 'users_role.id', '=', 'users.role_id')
                ->get();

        if ($users->count()) {
            foreach ($users as $row)
            {
                $hari = date('l', strtotime($row->created_at));
                $tanggal = substr($row->created_at, 8, 2);
                $bulan = substr($row->created_at, 5, 2);
                $tahun = substr($row->created_at, 0, 4);
                $jam = substr($row->created_at, 11, 8);

                $url = Helper::url();

                $datas[] = array(
                    'id' => $row->id,
                    'username' => $row->username,
                    'email' => Str::lower($row->email),
                    'foto' => ($row->foto == null ? $url."user/default.jpg" : $url."user/".$row->foto),
                    'role_id' => $row->role_id,
                    'role' => $row->role_name,
                    'status' => $row->status,
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
                'status' => false,
                'data' => [],
                'error' => 'Data not found!'
            ], 200);
        }
    }

    public function getById($id)
    {
        $user = User::orderBy('created_at', 'desc')->where('id', $id)->get();

        $url = Helper::url();

        if ($user->count()) {
            foreach ($user as $row)
            {
                $datas[] = array(
                    'id' => $row->id,
                    'username' => $row->username,
                    'email' => Str::lower($row->email),
                    'foto' => ($row->foto == null ? $url."user/default.jpg" : $url."user/".$row->foto),
                    'role_id' => $row->role_id,
                    'role' => $row->role_name,
                    'status' => $row->status,
                    'created_at' => $row->created_at,
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

    public function getByUsername(Request $request)
    {
        $username = User::where('username', $request->username)->get();

        if ($username->count()) {
            foreach ($username as $row)
            {
                return response()->json([
                    'username' => $row->username
                ], 200);
            }
        } else {
            return response()->json([
                'username' => ""
            ], 200);
        }
    }

    public function getByEmail(Request $request)
    {
        $email = User::where('email', $request->email)->get();

        if ($email->count()) {
            foreach ($email as $row)
            {
                return response()->json([
                    'email' => $row->email
                ], 200);
            }
        } else {
            return response()->json([
                'email' => ""
            ], 200);
        }
    }

    public function getRoleByStatus()
    {
        $role = Role::where('status', 1)->get();

        if ($role->count()) {
            foreach ($role as $row)
            {
                $datas[] = array(
                    'role_id' => $row->role_id,
                    'role' => $row->role
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

    public function store(Request $request)
    {
        if ($request->get('password') != $request->get('confirm_password')) {
            return response()->json([
                'status' => false,
                'message' => 'Password must be the same as Password confirmation!'
            ], 400);
        } else {
            $check_username = User::where('username', $request->username)->first();

            if (!$check_username)
            {
                $check_email = User::where('email', $request->email)->first();

                if (!$check_email)
                {
                    $file = $request->file('foto');

                    if ($file != "")
                    {
                        $filaname = null;
                        $file = null;

                        if ($request->hasFile('foto')) {
                            $file = $request->file('foto');
                            $filaname = Str::random(20).'.'.$file->extension();
                            $file->move(base_path('public/user'), $filaname);
                        }

                        $create = User::create([
                            'foto' => ($file != "" ? $filaname : null),
                            'username' => $request->username,
                            'email' => Str::lower($request->email),
                            'password' => Hash::make($request->input('password')),
                            'role_id' => $request->role_id,
                            'status' => $request->status
                        ]);

                        if ($create) {
                            return response()->json([
                                'status' => true,
                                'message' => 'Save User data successfully'
                            ], 200);
                        } else {
                            return response()->json([
                                'status' => false,
                                'message' => 'Save User data failed!'
                            ], 400);
                        }
                    } else {
                        $create = User::create([
                            'username' => $request->username,
                            'email' => Str::lower($request->email),
                            'password' => Hash::make($request->input('password')),
                            'role_id' => $request->role_id,
                            'status' => $request->status
                        ]);

                        if ($create) {
                            return response()->json([
                                'status' => true,
                                'message' => 'Save User data successfully'
                            ], 200);
                        } else {
                            return response()->json([
                                'status' => false,
                                'message' => 'Save User data failed!'
                            ], 400);
                        }
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'The Email you use is registered!'
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The Username you are using is registered!'
                ], 400);
            }
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $update = User::where('id', $request->id)
            ->update([
                'role_id' => $request->role_id,
                'status' => $request->status_user,
            ]);

        if ($update) {
            return response()->json([
                'status' => true,
                'message' => 'Edit User data successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Edit User data failed!'
            ], 400);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if ($user->foto != "") {
            $url = Helper::url();
            $file = Str::replace($url.'user/', '', $user->foto);
            unlink(base_path('public/user/' . $file));
        }

        $user->delete();

        if ($user) {
            return response()->json([
                'status' => true,
                'message' => 'Delete User data successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Delete User data failed!'
            ], 400);
        }
    }
}
