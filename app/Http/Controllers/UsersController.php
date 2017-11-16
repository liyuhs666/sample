<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Auth;

class UsersController extends Controller
{
    /**
     * 权限验证
     */
    public function __construct()
    {
        // 目前公开的情报
        $this->middleware('auth',[
            'except' => ['show','create','store']
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


    public function show(User $user)
    {	
    	return view('users.show', compact('user'));
    }


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

        Auth::login($user);
    	session()->flash('success',"It's a fine day with you around");
    	return redirect()->route('users.show',[$user]);
    }


   public function edit(User $user)
   {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
   }


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


   public function destroy(User $user)
   {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success','成功删除用户');
        return back();
   }


}