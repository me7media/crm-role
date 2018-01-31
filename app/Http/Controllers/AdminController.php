<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;
use Illuminate\Foundation\Auth\RegistersUsers;
use Session;
use Hash;

class AdminController extends Controller
{
    public function __construct() 
    {
        $this->middleware(['auth']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
             if (\Auth::user()->isAdmin()) {
        $users = User::all();

        return view('admin.index')->with('users', $users)->with('month', date('m'));
        } else return redirect('home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->isAdmin()) {
        $roles = Role::get();
        return view('admin.create', ['roles'=>$roles]);
    } else return redirect('home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         if (\Auth::user()->isAdmin()) {
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed'
        ]);
        $request['password'] = bcrypt($request['password']);
        $user = User::create($request->only('email', 'name', 'password'));

        $roles = $request['roles'];

        if (isset($roles)) {

            foreach ($roles as $role) {
            $role_r = Role::where('id', '=', $role)->firstOrFail();            
            $user->attachRole($role_r);
            }
        }        
Session::flash('message', "User successfully added");
        return redirect()->route('admin.index');
         } else return redirect('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('admin');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         if (\Auth::user()->isAdmin()) {
            $user = User::findOrFail($id);
            $roles = Role::get();

            return view('admin.edit', compact('user', 'roles'));
         } else return redirect('home');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

     if (\Auth::user()->isAdmin()) {
        if (Hash::check($request->adminpass, \Auth::user()->password)){

        $user = User::findOrFail($id);
        if($request->password){

        
            $this->validate($request, [
            'password'=>'required|min:6|confirmed'
            ]);
          
        } else 
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id,
            
        ]);
        $request['password'] = bcrypt($request['password']);
        $input = $request->only(['name', 'email', 'password']);
        $roles = $request['roles'];
        $user->fill($input)->save();

        if (isset($roles)) {        
            $user->roles()->sync($roles);            
        }        
        else {
            $user->roles()->detach();
        }
        Session::flash('message', "User successfully edited");
        return redirect()->back();
        } else {
            Session::flash('message', "Admin Password Incorrect!");
        return redirect()->back();
          }
         } else return redirect('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Auth::user()->isAdmin()) {
        $user = User::findOrFail($id);
        $user->datas()->delete();
        $user->delete();

        return redirect()->route('admin.index')
            ->with('flash_message',
             'User successfully deleted.');
          }  else return redirect('home');
    }

        public function profile($id, Request $request)
    {
        if (\Auth::user()->isAdmin()) {
if($request->year && $request->month){
        $year = $request->year;
        $month = $request->month;
} else {
       $year = date('Y');
       $month = date('m');
}
    $data = User::find($id)->datas()
            // ->where('user_id',\Auth::id())
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        return view('profile.index')->with(['datas'=>$data, 'year'=>$year,'month'=>$month]);
           } else return redirect('home');
    }
}
