<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator , Hash , Auth, Carbon\Carbon;
use Illuminate\Validation\Rule;
use Notification;
use App\Notifications\UserNotification;

class UserController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return view('admin.layouts.users.list');
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $user_role = Auth::user()->role;
        return view('admin.layouts.users.create',compact('user_role'));
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        },'Space Not Allow');
        
        $request->validate([
            'first_name' =>'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users|without_spaces',
            'password' =>'nullable|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).+$/|confirmed',
        ]);
        
        if($request->password != null)
        {
            $status = '1';
        }
        else{
            $status = '2';
        }
        
        $user = new User;
        $user->user_id = Auth::User()->id;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->role = $request->role;
        $user->status = $status;
        $user->password = Hash::make($request->password);
        $user->save();
        
        if(Auth::check())
        {
            return redirect()->route('password_set',[$user->email])->with('success','User Created Successfully' );
        }
        else{
            return redirect()->route('login');
        }
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */
    public function show(User $user)
    {
        $user = User::find($user->id);
        
        if($user)
        {
            return view('admin.layouts.users.show',compact('user'));
        }
        else
        {
            return redirect()->route('user.index')->with('warning','Record Not Found');
        }
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */
    public function edit(User $user)
    {
        $user = User::find($user->id);
        $user_role = Auth::user()->role;
        if(Auth::user()->id != $user->id)
        {
            return view('admin.layouts.users.edit',compact('user','user_role'));
        }
        else{
            return redirect()->route('user.show',$user->id)->with('warning','Sorry! You Could Not Edit These Record');
        }
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, User $user)
    {
        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        },'Space Not Allow');
        
        $request->validate([
            'first_name' =>'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'username' => 'required|without_spaces|unique:users,username,'.$user->id,
        ]);
        if($request->has('status') == 'on')
        {
            $status = 1;
        }
        else{
            $status = 0;
        }
        
        $user = User::find($user->id);
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
            'status' => $status,
        ]);
        $user->update();
        
        return redirect()->route('user.index')->with('info','Record Updated Successfully');
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\User  $user
    * @return \Illuminate\Http\Response
    */
    public function destroy(User $user)
    {
        $auth_id = Auth::User()->id;
        
        if($auth_id != $user->id)
        {
            $user = User::find($user->id)->delete();
            return redirect()->route('user.index')->with( 'warning','User Deleted Successfully');
        }
        else{
            return redirect()->route('user.index')->with('warning','Sorry! You Could not delete yourself record');
        }
    }
    
    /**
    * Display the data from ajax.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function ajax_fetchuser(Request $request)
    {
        $perpage = 10;
        
        $columns = array(
            0 => 'id',
            1 => 'first_name',
            2 => 'role',
            3 => 'last_active',
            4 => 'status',
            5 => 'action',
        );
        
        $filter_data = (!empty($request->search['value']))?$request->search['value']:"";
        
        if(!empty($filter_data))
        {
            $search = $request->search['value'];
            $auth_role = Auth::user()->role;
            if($auth_role == "super_admin")
            {
                $users = User::where('first_name','like','%'.$search.'%')
                ->orWhere('last_name','like','%'.$search.'%')
                ->orWhere('email','like','%'.$search.'%')
                ->orWhere('role','like','%'.$search.'%');
            }
            elseif($auth_role == "admin")
            {
                $users = User::whereIn('role',['admin','student'])
                ->where(function($q) use ($search){
                    $q->where('first_name','like','%'.$search.'%')
                    ->orWhere('last_name','like','%'.$search.'%')
                    ->orWhere('email','like','%'.$search.'%')
                    ->orWhere('role','like','%'.$search.'%');
                });
            }
            elseif($auth_role == 'student'){
                $users = User::whereIn('role',['student'])
                ->where(function($q) use ($search){
                    $q->where('first_name','like','%'.$search.'%')
                    ->orWhere('last_name','like','%'.$search.'%')
                    ->orWhere('email','like','%'.$search.'%')
                    ->orWhere('role','like','%'.$search.'%');
                });
            }
        }
        else{
            $auth_role = Auth::user()->role;
            if($auth_role == "super_admin")
            {
                $users = User::select('*');
            }
            elseif($auth_role == "admin")
            {
                $users = User::whereIn('role',['admin','student']);
            }
            elseif($auth_role == 'student'){
                $users = User::whereIn('role',['student']);
            }
        }
        
        $data = array();
        $limit=(!empty($request['length']))?$request['length']:$perpage;
        $offset=(!empty($request['start']))?$request['start']:0;
        $total_data = $users->count();
        $total_filtered = $users->count();
        
        $set_order = isset($request['order']['0']['column'])?$request['order']['0']['column']:"";
        if($set_order != "")
        {
            $order_by = $columns[$request['order']['0']['column']];
            $order = $request['order']['0']['dir'];
            
            if($order == "asc")
            {
                $users = $users->get()->sortBy($order_by,SORT_REGULAR)->skip($offset)->take($limit);
            }
            else{
                $users = $users->get()->sortByDesc($order_by,SORT_REGULAR)->skip($offset)->take($limit);
            }
        }
        else{
            $users = $users->latest();
            $users = $users->offset($offset)->limit($limit)->get();
        }
        
        foreach($users as $user)
        {   
            if($user->status == 2){
                $status = 'Invited <br><a href="'.route('password_set',[$user->email]).'" class="link-success">Resend</a>';
            }elseif($user->status == 1){
                $status = 'Active';
            }else{
                $status = "Disable";
            }
            
            if($user->last_active){
                $last_active = date('d M Y',strtotime($user->last_active));
            }else{
                $last_active = ''; 
            }
            
            if($user->role == "super_admin"){
                $role = "Super Admin";
            }elseif($user->role == "admin"){
                $role = "Admin";
            }elseif($user->role == "student"){
                $role = "Student";
            }
            
            if(Auth::user()->id != $user->id)
            {
                $checkbox = '<input type="checkbox" value="'.$user->id.'" id="user_checkbox" />';
            }else{
                $checkbox = "";
            }
            
            $nested_data = array();
            
            $nested_data[] = $checkbox;
            $nested_data[] = $user->first_name.' '.$user->last_name.'<br><text style="color:grey;">'.$user->email;
            $nested_data[] = $role;
            $nested_data[] = $last_active;
            $nested_data[] = $status;
            
            if(Auth::user()->id == $user->id)
            {
                $nested_data[] = '
                <a href="'.route('user.show',$user->id).'" class="badge badge-info"><label class="text-info">Show</label>
                </a>
                ';
            }
            else{
                $nested_data[] = '
                <a href="'.route('user.show',$user->id).'" class="badge badge-info"><label class="text-info">Show</label>
                </a>
                <a href="'. route('user.edit',$user->id) .'" class="badge badge-warning">
                <label class="text-warning">Edit</label>
                </a>
                <a href="#" class="badge badge-danger delete"  data-value="'.$user->id.'" >
                <label class="text-danger">Delete</label>
                </a> 
                <form action="'.route('user.destroy',$user->id ) .'" method="post" id="delete_form'.$user->id.'" >
                '.csrf_field().method_field('DELETE').'
                </form> 
                ';
            }    
            $data[] = $nested_data;
        }
        
        $json_data = array(
            "draw" => intval($request['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($total_data),  // total number of records
            "recordsFiltered" => intval($total_filtered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        echo json_encode($json_data);  // send data as json format
    }
    
    /**
    * Display Lauout page with email.
    *
    * @param  string  $email
    * @return \Illuminate\Http\Response
    */
    public function password_setview($email)
    {
        return view('admin.layouts.users.set_user_password',compact('email'));
    }
    
    /**
    * send notification.
    *
    * @param  string  $email
    * @return \Illuminate\Http\Response
    */
    public function password_set($email)
    {
        Notification::route('mail',$email)->notify(new UserNotification($email));
        return redirect()->route('user.index')->with( trans('translate.alert_message_classname.success'),trans('translate.message.send_mail'));
    }
    
    /**
    * set password and change status 
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */ 
    public function password_set_user(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).+$/|confirmed',
            'password_confirmation' => 'required',
        ]);
        
        $users = User::where('email',$request->email)->first();
        
        $users->password = Hash::make($request->password);
        $users->status = 1;
        $users->update();
        return redirect()->route('home')->with(trans('translate.alert_message_classname.success'),trans('translate.message.password_set'));
    }
    
    
    /**
    * Change Multiple User Status through ajax.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function change_multiple_status(Request $request)
    {
        $data = array();
        if($request->id)
        {
            $user_ids = $request->id;
            foreach($user_ids as $key => $value)
            {
                if($request->status == 1 )
                {
                    $user = User::where('id',$value)->get()->first();
                    $user->update(['status' => 1]);
                }
                if($request->status == 0)
                {
                    $user = User::where('id',$value)->get()->first();
                    $user->update(['status' => 0]);
                }
            }
            $class = trans('translate.alert_message_classname.update_status_success');
            $data = [$class,trans('translate.message.change_status_success')];
            return response($data);
        }
        
        $class = trans('translate.alert_message_classname.update_status_error');
        $data = [$class,trans('translate.message.change_status_error')];
        return response($data);
    }
}
