<?php

namespace App\Http\Controllers;

use App\Models\Theme;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function getByUserId($id)
    {
        $theme = Theme::where('user_id', $id)->first();

        if ($theme) {
            return response()->json([
                'status' => true,
                'data' => [
                    'theme_id' => $theme->theme_id,
                    'user_id' => $theme->user_id,
                    'title_apps' => ($theme->title_apps == "" ? "Content Management System" : $theme->title_apps),
                    'title_header' => ($theme->title_header == "" ? "CMS" : $theme->title_header),
                    'subtitle_header' => ($theme->subtitle_header == "" ? "Content Management System" : $theme->subtitle_header),
                    'title_footer' => ($theme->title_footer == "" ? "Copyright © 2024 Content Management System. All rights reserved." : $theme->title_footer),
                    'data_layout_mode' => ($theme->data_layout_mode == "" ? "light" : $theme->data_layout_mode),
                    'data_topbar' => ($theme->data_topbar == "" ? "light" : $theme->data_topbar),
                    'data_sidebar' => ($theme->data_sidebar == "" ? "light" : $theme->data_sidebar),
                    'created_at' => $theme->created_at,
                    'updated_at' => $theme->updated_at
                ]
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'data' => [
                    'title_apps' => "Content Management System",
                    'title_header' => "CMS",
                    'subtitle_header' => "Content Management System",
                    'title_footer' => "Copyright © 2024 Content Management System. All rights reserved.",
                    'data_layout_mode' => "light",
                    'data_topbar' => "light",
                    'data_sidebar' => "light",
                ]
            ], 200);
        }
    }

    public function update(Request $request)
    {
        $getData = Theme::where('user_id', $request->user_id)->first();

        if ($getData) {
            $update = $getData->update([
                'title_apps' => $request->title_apps,
                'title_header' => $request->title_header,
                'subtitle_header' => $request->subtitle_header,
                'title_footer' => $request->title_footer,
                'data_layout_mode' => $request->data_layout_mode,
                'data_topbar' => $request->data_topbar,
                'data_sidebar' => $request->data_sidebar
            ]);

            if ($update) {
                return response()->json([
                    'status' => true,
                    'message' => 'Change App Theme successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Change App Theme failed!'
                ], 400);
            }
        } else {
            $store = new Theme;
            $store->user_id = $request->user_id;
            $store->title_apps = $request->title_apps;
            $store->title_header = $request->title_header;
            $store->subtitle_header = $request->subtitle_header;
            $store->title_footer = $request->title_footer;
            $store->data_layout_mode = $request->data_layout_mode;
            $store->data_topbar = $request->data_topbar;
            $store->data_sidebar = $request->data_sidebar;
            $store->save();

            if ($store) {
                return response()->json([
                    'status' => true,
                    'message' => 'Change App Theme successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Change App Theme failed!'
                ], 400);
            }
        }
    }
}
