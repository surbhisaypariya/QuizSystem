<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return view('admin.layouts.subject.list');
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('admin.layouts.subject.create');
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $subject = new Subject();
        $subject->name = $request->name;
        $subject->status = $request->status == 'on'?'1':'0';
        $subject->save();
        
        return redirect()->route('subject.index')->with('success','Subject Saved Successfully');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Models\admin\subject  $subject
    * @return \Illuminate\Http\Response
    */
    public function show(subject $subject)
    {
        //
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\admin\subject  $subject
    * @return \Illuminate\Http\Response
    */
    public function edit(subject $subject)
    {
        return view('admin.layouts.subject.edit',compact('subject'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\admin\subject  $subject
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, subject $subject)
    {
        $subject->update([
            'name' => $request->name,
            'status' => $request->status == 'on'?'1':'0',
        ]);
        $subject->update();
        return redirect()->route('subject.index')->with('info','Subject Updated Successfully');
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\admin\subject  $subject
    * @return \Illuminate\Http\Response
    */
    public function destroy(subject $subject)
    {
        $subject->delete();
        return redirect()->route('subject.index')->with('error','Subject Deleted Successfully');
    }
    
    /**
    * Display the data from ajax.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function ajax_fetchsubject(Request $request)
    {
        $perpage = 10;
        
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'created_at',
            3 => 'status',
            4 => 'Action',
        );
        
        $filter_data =  $filter_data = (!empty($request->search['value']))?$request->search['value']:"";
        if(!empty($filter_data))
        {
            $search=$request['search']['value'];
            $subjects = Subject::where('name','like','%'.$search.'%');
        }
        else{
            $subjects = Subject::select('*');
        }
        
        $data = array();
        $limit=(!empty($request['length']))?$request['length']:$perpage;
        $offset=(!empty($request['start']))?$request['start']:0;
        $total_data = $subjects->count();
        $total_filtered = $subjects->count();
        
        $set_order = isset($request['order']['0']['column'])?$request['order']['0']['column']:"";
        if($set_order != "")
        {
            $order_by = $columns[$request['order']['0']['column']];
            $order = $request['order']['0']['dir'];
            
            if($order == "asc")
            {
                $subjects = $subjects->get()->sortBy($order_by,SORT_REGULAR)->skip($offset)->take($limit);
            }
            else{
                $subjects = $subjects->get()->sortByDesc($order_by,SORT_REGULAR)->skip($offset)->take($limit);
            }
        }
        else{
            $subjects = $subjects->latest();
            $subjects = $subjects->offset($offset)->limit($limit)->get();
        }
        
        foreach($subjects as $subject)
        {
            $nested_data = array();
            $nested_data[] = $subject->name;
            $nested_data[] = $subject->created_at->format('M d, Y h:i A');
            $nested_data[] = $subject->status == 1?"Active":"Disable";
            $nested_data[] = '
            <a href="'. route('subject.edit',$subject->id) .'" class="badge badge-warning">
            <label class="text-warning">Edit</label>
            </a>
            <a href="#" class="badge badge-danger delete"  data-value="'.$subject->id.'" >
            <label class="text-danger">Delete</label>
            </a> 
            <form action="'.route('subject.destroy',$subject->id ) .'" method="post" id="delete_form'.$subject->id.'" >
            '.csrf_field().method_field('DELETE').'
            </form>
            ';
            
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
}
