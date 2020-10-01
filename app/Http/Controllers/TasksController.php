<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TasksController extends Controller
{
    //User::tasks()
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            $tasks = $user->task()->orderBy('created_at', 'desc')->paginate(10);
            
            //$tasks = $user_id::all(); //追加 9月22日

            $data = [
                'user' => $user,
                'tasks' => $tasks,
                //'user_id' => $user_id, //追加 9月22日
            ];
        }

        // Welcomeビューでそれらを表示 store
        return view('welcome', $data);
    }
    
    public function create() //追加
    {
        //
        $task = new Task;

        // メッセージ作成ビューを表示
        return view('tasks.create', [
            'status' => $task,
            'task' => $task,
        ]);
    }
    
    /*public function show(Request $request)
    {
        $request->validate([
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);
        
        // タスクを作成
        $task = new Task;  //追加
        $task->status = $request->status;    // 追加
        $task->content = $request->content; //追加
        $task->user_id = \Auth::user()->id;
        $task->save();  //追加

        // メッセージ詳細ビューでそれを表示
        return view('tasks.show', [
            'status' => $task,
            'task' => $task,
        ]);
        
         return redirect('/');
    }*/
    
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);
        
        // タスクを作成
        $task = new Task;  //追加
        $task->status = $request->status;    // 追加
        $task->content = $request->content; //追加
        $task->user_id = \Auth::user()->id;
        $task->save();  //追加

        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        // $request->user()->task()->create([
        //     'content' => $request->content,
        // ]);

        // 前のURLへリダイレクトさせる
        // return back();
        return redirect('/');

    }
    
    public function show($id)
    {
        //
        $task = Task::findOrFail($id);

        // メッセージ詳細ビューでそれを表示
        return view('tasks.show', [
            'status' => $task,
            'task' => $task,
        ]);
    }
    
    public function edit($id)
    {
        //
        $task = Task::findOrFail($id);

         //メッセージ編集ビューでそれを表示
        return view('tasks.edit', [
            'status' => $task,
            'task' => $task,
        ]);
    }
    
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);
        
        //
        $task = Task::findOrFail($id);
        // メッセージを更新
        $task->status = $request->status;    // 追加
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }
    
    public function destroy($id)
    {
        // idの値で投稿を検索して取得
        $task = \App\Task::findOrFail($id);

        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        // 前のURLへリダイレクトさせる
        return back();
    }
}
