<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;

use App\Models\User;
use App\Models\UserFace;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function profile($id)
    {
        $user = User::where('users.id', $id)->get();

        if ($user->count()) {
            foreach ($user as $row)
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
                    'email' => $row->email,
                    'foto' => ($row->foto == null ? $url."user/default.jpg" : $url."user/".$row->foto),
                    'role_id' => $row->role_id,
                    'status' => $row->status,
                    'created_at_custom' => Helper::dayName($hari).", ".$tanggal." ".Helper::monthName($bulan)." ".$tahun." ".$jam,
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

    public function change_profile(Request $request)
    {
        $id = $request->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $file = $request->file('foto');

        if ($file != "") {
            $user = User::find($id);

            if ($user->foto != "") {
                unlink(base_path('public/user/' . $user->foto));
            }

            $filaname = null;
            $file = null;

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filaname = Str::random(20).'.'.$file->extension();
                $file->move(base_path('public/user'), $filaname);
            }

            $update = User::where('id', $id)
                ->update([
                    'username' => $request->input('username'),
                    'email' => $request->input('email'),
                    'foto' => $filaname
                ]);
        } else {
            $update = User::where('id', $id)
                ->update([
                    'username' => $request->input('username'),
                    'email' => $request->input('email')
                ]);
        }

        if ($update) {
            return response()->json([
                'status' => true,
                'message' => 'Change profile success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Change profile failed!'
            ], 400);
        }
    }

    public function check_password(Request $request)
    {
        $id = $request->input('id_pass');
        $password = $request->input('password');

        $user = User::where('id', $id)->first();

        $isValidPassword = Hash::check($password, $user->password);

        if ($isValidPassword) {
            return response()->json([
                'status' => true
            ], 200);
        } else {
            return response()->json([
                'status' => false
            ], 200);
        }
    }

    public function change_password(Request $request)
    {
        $id = $request->id;

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $data_user = User::find($id);
        $old_password = $data_user->password;

        if (!(Hash::check($request->get('current_password'), $old_password))) {
            return response()->json([
                'status' => false,
                'message' => 'The old password you entered incorrectly!'
            ], 400);
        } elseif ($request->get('new_password') != $request->get('confirm_password')) {
            return response()->json([
                'status' => false,
                'message' => 'The new Password must be the same as the confirmation of the new Password!'
            ], 400);
        } else {
            $update = User::where('id', $id)
                ->update([
                    'password' => Hash::make($request->get('new_password'))
                ]);

            if ($update) {
                return response()->json([
                    'status' => true,
                    'message' => 'Change Password successfully, please re-login!'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Change Password failed!'
                ], 400);
            }
        }
    }

    public function face_enroll(Request $request)
    {
        $user_id = $request->user_id;

        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $create = UserFace::create([
            'user_id' => $user_id,
            'image_id' => $request->image_id
        ]);

        if ($create) {
            return response()->json([
                'status' => true,
                'message' => 'Enrollment success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Enrollment failed!'
            ], 400);
        }
    }

    public function list_face($id)
    {
        $faces = UserFace::where('user_id', $id)->get();

        if ($faces->count()) {
            foreach ($faces as $row)
            {
                $hari = date('l', strtotime($row->created_at));
                $tanggal = substr($row->created_at, 8, 2);
                $bulan = substr($row->created_at, 5, 2);
                $tahun = substr($row->created_at, 0, 4);
                $jam = substr($row->created_at, 11, 8);

                $datas[] = array(
                    'id' => $row->id,
                    'user_id' => $row->user_id,
                    'image_id' => $row->image_id,
                    'created_at_custom' => Helper::dayName($hari).", ".$tanggal." ".Helper::monthName($bulan)." ".$tahun." ".$jam,
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
                'data' => [],
                'message' => 'Data not found!'
            ], 200);
        }
    }

    public function remove_face($id)
    {
        $face = UserFace::where('image_id', $id);
        $remove = $face->delete();

        if ($remove) {
            return response()->json([
                'status' => true,
                'message' => 'Remove face successful'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Remove face failed!'
            ], 400);
        }
    }
}
