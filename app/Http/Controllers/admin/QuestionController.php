<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\question;
use Illuminate\Http\Request;

use App\Models\admin\subject;

class QuestionController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return view('admin.layouts.question.list');
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $subjects = Subject::all();
        return view('admin.layouts.question.create',compact('subjects'));
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $question = new Question();
        $question->question = $request->question;
        $question->option_1 = $request->option_1;
        $question->option_2 = $request->option_2;
        $question->option_3 = $request->option_3;
        $question->option_4 = $request->option_4;
        $question->answer = $request->answer;
        $question->status = $request->status = 'on'?'1':'0';
        $question->save();
        
        $subject_id = $request->subject;
        $question_id = $question->id;
        $question->subject()->attach($subject_id);
        
        return redirect()->route('question.index')->with('success','Question Addded Successfully');
        
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Models\admin\question  $question
    * @return \Illuminate\Http\Response
    */
    public function show(question $question)
    {
        //
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\admin\question  $question
    * @return \Illuminate\Http\Response
    */
    public function edit(question $question)
    {
        $subjects = Subject::all();
        $subject_id =  $question->subject->pluck('id')->first();
        return view('admin.layouts.question.edit',compact('subjects','question','subject_id'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\admin\question  $question
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, question $question)
    {
        $question->update([
            'question' => $request->question,
            'option_1' => $request->option_1, 
            'option_2' => $request->option_2, 
            'option_3' => $request->option_3, 
            'option_4' => $request->option_4, 
            'answer' => $request->answer,
            'status' => $request->status == 'on'?'1':'0',
        ]);
        $question->update();
        
        $subject_id = $request->subject;
        $question_id = $question->id;
        $question->subject()->sync($subject_id);
        
        return redirect()->route('question.index')->with('info','Question Updated Successfully');
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\admin\question  $question
    * @return \Illuminate\Http\Response
    */
    public function destroy(question $question)
    {
        $question->subject()->sync([]);
        $question->delete();
        return redirect()->route('question.index')->with('error','Question Deleted Successfully');
    }
    
    /**
    * Display the data from ajax.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function ajax_fetchquestion(Request $request)
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
            $questions = Question::where('name','like','%'.$search.'%');
        }
        else{
            $questions = Question::select('*');
        }
        
        $data = array();
        $limit=(!empty($request['length']))?$request['length']:$perpage;
        $offset=(!empty($request['start']))?$request['start']:0;
        $total_data = $questions->count();
        $total_filtered = $questions->count();
        
        $set_order = isset($request['order']['0']['column'])?$request['order']['0']['column']:"";
        if($set_order != "")
        {
            $order_by = $columns[$request['order']['0']['column']];
            $order = $request['order']['0']['dir'];
            
            if($order == "asc")
            {
                $questions = $questions->get()->sortBy($order_by,SORT_REGULAR)->skip($offset)->take($limit);
            }
            else{
                $questions = $questions->get()->sortByDesc($order_by,SORT_REGULAR)->skip($offset)->take($limit);
            }
        }
        else{
            $questions = $questions->latest();
            $questions = $questions->offset($offset)->limit($limit)->get();
        }
        
        foreach($questions as $question)
        {
            $nested_data = array();
            $nested_data[] = $question->question;
            $nested_data[] = $question->subject->pluck('name');
            $nested_data[] = $question->created_at->format('M d, Y h:i A');
            $nested_data[] = $question->status == 1?"Active":"Disable";
            $nested_data[] = '
            <a href="'. route('question.edit',$question->id) .'" class="badge badge-warning">
            <label class="text-warning">Edit</label>
            </a>
            <a href="#" class="badge badge-danger delete"  data-value="'.$question->id.'" >
            <label class="text-danger">Delete</label>
            </a> 
            <form action="'.route('question.destroy',$question->id ) .'" method="post" id="delete_form'.$question->id.'" >
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
