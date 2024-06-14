<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Models\Task;

class TaskController extends Controller
{
    public function getTasks() {
        $data = [];
        $user_id = null;
        $data = [];
        if(!is_null(Auth::user())) {
            $user_id = Auth::user()->id;
            $data = Task::where('t_user_id', $user_id)->get();
        }
        
        return $data;
    }

    public function create(Request $request) {
        $user_id = null;
        if(!is_null(Auth::user())) {
            $user_id = Auth::user()->id;
        }

        $files_path = public_path() . '/files';
        if(!is_dir($files_path)) {
            mkdir($files_path);
        }
        $dir_path = public_path() . '/files/task_files';
        if(!is_dir($dir_path)) {
            mkdir($dir_path);
        }
        $filepath = $request->file('t_file')->store('task_files', ['disk' => 'files']);

        Task::create([
            't_user_id' => $user_id,
            't_title' => $request->t_title,
            't_content' => $request->t_content,
            't_status' => $request->t_status,
            't_file' => $filepath,
            't_is_published' => $request->t_is_published,
        ]);
        return back();
    }

    public function update(Request $request) {
        $column = 't_' . $request->column;
        return Task::where('t_id', $request->id)
            ->update([
                $column => $request->value
            ]);
    }
}
