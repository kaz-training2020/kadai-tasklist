<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // //
        // // タスク一覧を取得
        // $tasks = Task::all();

        // // タスク一覧ビューでそれを表示
        // return view('tasks.index', [
        //     'tasks' => $tasks,
        // ]);
        
        if (\Auth::check()) { // 認証済みの場合
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            return view('tasks.index', [
            'tasks' => $tasks,
        ]);
        }
        return redirect('login');
        
    }

    // getでtask/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task = new Task;

        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        // タスクを作成
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->user_id = $request->user()->id;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // getでtasks/idにアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        $task = Task::findOrFail($id);
        
        // 認証
        if (\Auth::id() === $task->user_id) {
            return view('tasks.show', [
            'task' => $task,
        ]);
        }else {
            return redirect('/');
        }
        
        
        // // idの値でメッセージを検索して取得
        // $task = Task::findOrFail($id);

        // // タスク詳細ビューでそれを表示
        // return view('tasks.show', [
        //     'task' => $task,
        // ]);
    }

    // getでtasks/id/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        // 認証
        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', [
            'task' => $task,
        ]);
        }else {
            return redirect('/');
        }
        
        // // idの値でタスクを検索して取得
        // $task = Task::findOrFail($id);

        // // タスク編集ビューでそれを表示
        // return view('tasks.edit', [
        //     'task' => $task,
        // ]);
    }

    // putまたはpatchでtasks/idにアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);

        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // タスクを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->user_id = $request->user()->id;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }


    // deleteでtasks/idにアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        // 認証
        if (\Auth::id() === $task->user_id) {
            $task->delete();
            return redirect('/');
        } else {
            return redirect('/');
        }
        
        
        // // idの値でメッセージを検索して取得
        // $task = Task::findOrFail($id);
        // // メッセージを削除
        // $task->delete();

        // // トップページへリダイレクトさせる
        // return redirect('/');
    }

}
