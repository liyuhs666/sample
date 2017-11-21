<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{
    /**
     * 权限验证
     */
    public function __construct()
    {
        // 目前公开的情报
        $this->middleware('auth',[
            'except' => ['show','create','store', 'index', 'confirmEmail']
        ]);

        // 客人只允许访问登录页面?
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }


    public function index(){
        $users = User::paginate(7);
        return view('users.index',compact('users'));
    }


    public function create()
    {	
        return view('users.create');
    }


    public function caozuo()
    {
        $user = User::find(3);
        $user->is_admin = 1;
        $user->save();
    }



    /**
     * 注册新用户
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
    	$this->validate($request,[
    			'name' => 'required|max:50',
    			'email' => 'required|email|unique:users|max:255',
    			'password' => 'required|confirmed|min:6'
    		]);

    	$user = User::create([
    			'name' =>$request->name,
    			'email' => $request->email,
    			'password' => bcrypt($request->password),
    		]);

        // 原本直接登录
        // Auth::login($user);
    	// session()->flash('success',"It's a fine day with you around");
    	// return redirect()->route('users.show',[$user]);
    
        //现在是发送邮箱验证
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
    }


    /**
     * 发送激活邮件
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'aufree@yousails.com';
        $name = 'Aufree';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }


    /**
     * 点击邮件中的链接进行激活
     * @param  [type] $token [description]
     * @return [type]        [description]
     */
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }


    /**
     * 编辑用户信息
     * @param  User   $user [description]
     * @return [type]       [description]
     */
   public function edit(User $user)
   {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
   }



   /**
    * 更新用户信息
    * @param  User    $user    [description]
    * @param  Request $request [description]
    * @return [type]           [description]
    */
   public function update(User $user,Request $request)
   {

        $request->changepsd == 1 ? $vali = '|required' : $vali = '';
             
        $this->validate($request,[
            'name' =>'required|max:50',
            'password' => 'nullable|confirmed|min:6'.$vali
        ]);

         $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;

        if($request->password){
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success','修改资料成功');
        return redirect()->route('users.show',$user->id);

   }



   /**
    * 退出登录
    * @param  User   $user [description]
    * @return [type]       [description]
    */
   public function destroy(User $user)
   {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success','成功删除用户');
        return back();
   }


   /**
    *  显示用户的主页微博
    * @param  User   $user [description]
    * @return [type]       [description]
    */
   public function show(User $user)
   {
        $statuses = $user->statuses()
                         ->orderBy('created_at','desc')
                         ->paginate(30);
        return view('users.show',compact('user','statuses'));
   }



    public function followings(User $user)
    {
        $users = $user->followings()->paginate(10);
        $title = '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    public function followers(User $user)
    {
        $users = $user->followers()->paginate(10);
        $title = '粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }


}