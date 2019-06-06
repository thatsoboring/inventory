<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Input;

class CategoryController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'code' => 'required|unique:categories|max:30',
            'name' => 'required|unique:categories|max:255',
        ]);

        Category::create($request->all());
     
    }

    public function get_categories(){
        $categories = Category::orderBy('created_at')->paginate(10);

        return response()->json($categories);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $category = Category::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        print_r($category);
        //
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
        $request->validate([
            'code' => 'required|max:30|unique:categories,code,'.$id,
            'name' => 'required|max:255|unique:categories,name,'.$id,
        ]);
        
        $category = Category::findOrFail($id);
        $category->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Category::destroy($id);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function permanentlyDelete($categoryID){

        try{
            Category::onlyTrashed()->find($categoryID)->forceDelete();
        }catch(\Exception $e){
            // return $e->getMessage();
            //409 error is a client error: conflict
            return ['status' => 409, 'ErrMsg' => 'Category is already used in other transaction.'];
        }
        
    }

    public function searchCategories(){
        $q = Input::get ( 'q' );
        if($q != ""){
            $categories = Category::where ( 'name', 'LIKE', '%' . $q . '%' )->orWhere ( 'code', 'LIKE', '%' . $q . '%' )->paginate (10)->setPath ( '' );
            $categories->appends ( array (
                        'q' => $q
                ) );
            
            return response()->json($categories);
       
        }else{
            $categories = Category::orderBy('created_at')->paginate(10);
            return response()->json($categories);
        }
   }

   public function trashbin(){

        return view('categories.trash');
   }

   public function getSoftDeletesCategories(){
    
        $deleted_categories = Category::orderBy('deleted_at', 'desc')->onlyTrashed()->paginate(10);
        return response()->json($deleted_categories);
   }

   public function restore($categoryID){
        Category::onlyTrashed()->find($categoryID)->restore();
   }
}
