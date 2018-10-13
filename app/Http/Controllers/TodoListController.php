<?php

namespace App\Http\Controllers;

use App\TodoList;
use Illuminate\Http\Request;
use Image;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     return view('todo_list');
 }


 public function TodoList(){


    $result = TodoList::orderBy('id','desc')->paginate(5);

    return $result;
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
   
   // image validation rules added in appserviceproviders 

    //     $messages = [
    // 'base64image' => 'The :field must be an image.',
    // ];
      $this->validate($request, [
        'name'  => 'required',
        'title' => 'required',
        'image' => 'required|base64image'
    ],[
    'base64image' => 'The :attribute field must be an image.',
    ]);

      try{

        $imageData = $request->get('image');
        $fileName = uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
        Image::make($request->get('image'))->save(public_path('images/').$fileName);


        $todo = new TodoList;
        $todo->name = $request->name;
        $todo->title = $request->title;
        $todo->image = $fileName;

        $todo->save();

        return response()->json(['status'=>'success','message'=>'User successfully updated !']);
    }

    catch(\Exception $e){
        return response()->json(['status'=>'error','message'=>'Something Error Found !, Please try again']);
    }


}

    /**
     * Display the specified resource.
     *
     * @param  \App\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function show(TodoList $todoList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function edit(TodoList $todoList)
    {
       return $todoList;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
     $this->validate($request, [
        'name'  => 'required',
        'title' => 'required',
            // 'image' => 'required|image64:jpeg,jpg,png'
    ]);
      

     try{


           $todo = TodoList::find($id);

   

        if($request->imageStatus == 1){ 
           if(file_exists('images/'.$todo->image) && !empty($todo->image)){

            unlink('images/'.$todo->image);
        }
             $imageData = $request->get('image');

        $fileName = uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
        Image::make($request->get('image'))->save(public_path('images/').$fileName);
        $todo->image = $fileName;

    }
 
    $todo->name = $request->name;
    $todo->title = $request->title;
    $todo->update();

    return response()->json(['status'=>'success','message'=>'Todo List successfully updated !']);
}

catch(\Exception $e){

    return response()->json(['status'=>'error','message'=>'Something Error Found !, Please try again']);

}
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TodoList  $todoList
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     $todo =  TodoList::find($id);

     if(file_exists('images/'.$todo->image) && !empty($todo->image)){

        unlink('images/'.$todo->image);
    }

    $todo->delete();

    return response()->json(['status'=>'success','message'=>'Delete Success']);
}
}
