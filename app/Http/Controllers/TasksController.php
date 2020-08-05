<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加

class TasksController extends Controller
{
    // getでmessages/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        
        // メッセージ一覧を取得
        //   $tasks = Task::all();

         $data = []; //誰もログインしていなければ空白

         if (\Auth::check()) { // 認証済みの場合
           // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を取得
            $tasks = $user->tasks()->get();
            
             $data = [
                        'tasks' => $tasks,
                     ];
                     
           // メッセージ一覧ビューでそれを表示
           return view('tasks.index',  $data);
         }
         else
         {
            return view('welcome');
         }
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   // getでmessages/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $tasks = new Task;

        // メッセージ作成ビューを表示
        return view('tasks.create', [
            'tasks' => $tasks,
        ]);
    }

    // postでmessages/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        $request->validate([
            'content'=> 'required|max:255',
            'status'=>'required|max:10',
            ]);
            
      // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
      $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
       ]);
        
        // メッセージを作成
      //  $task = new Task;
     //  $task->content = $request->content;
     //   $task->status = $request->status;
     //   $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

     // getでmessages/idにアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
      if (\Auth::id()  == $task->user_id) {
             // メッセージ詳細ビューでそれを表示
            return view('tasks.show', [
                'task' => $task,
            ]);
        }
        else
        {
                 // トップページへリダイレクトさせる
                return redirect('/');
        }
       
    }

   // getでmessages/id/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

 if (\Auth::id()  == $task->user_id) {
     
        // メッセージ編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
 }
        else
        {
                 // トップページへリダイレクトさせる
                return redirect('/');
        }
    }

    // putまたはpatchでmessages/idにアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
         // バリデーション
        $request->validate([
            'content' => 'required|max:255',
            'status' => 'required|max:10',
        ]);

        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
         if (\Auth::id()  == $task->user_id) {
        // メッセージを更新
        $task -> status = $request->status;
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
        
         }
        else
        {
                 // トップページへリダイレクトさせる
                return redirect('/');
        }
    }

    // deleteでmessages/idにアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
           if (\Auth::id()  == $task->user_id) {
        // メッセージを削除
        $task->delete();

        // トップページへリダイレクトさせる
        return redirect('/');
        
           }
        else
        {
                 // トップページへリダイレクトさせる
                return redirect('/');
        }
    }
}
