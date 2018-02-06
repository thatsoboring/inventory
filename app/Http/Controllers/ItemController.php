<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Category;
use Illuminate\Support\Facades\Input;

class ItemController extends Controller
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
        return view('items.index');
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
        $request->validate([
            'category_id' => 'required',
            'code' => 'required|unique:items|max:30',
            'name' => 'required|unique:items|max:255',
        ],
        [
            'category_id.required' => 'The category field is requried.'
        ]
        );

        Item::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
            'category_id' => 'required',
            'code'  => 'required|max:30|unique:items,code,'.$id,
            'name' => 'required|max:250|unique:items,name,'.$id
        ],
        [
            'category_id.required' => 'The category field is requried.'
        ]);

        $item = Item::find($id);
        $item->update($request->all());

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
            Item::destroy($id);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function getCategories(){
        $categories = Category::withTrashed()->orderBy('name','asc')->get();

        return response()->json($categories);
    }

    public function getItems(){
        $items = Item::with('category')->orderBy('name', 'asc')->paginate(10);

        return response()->json($items);
    }

    public function searchItems(){

        $q = Input::get ( 'q' );

        if($q != ''){
            $items = Item
            ::join('categories', 'items.category_id', '=', 'categories.id')
            ->with('category')
            ->where ( 'items.name', 'LIKE', '%' . $q . '%' ) 
            ->orWhere ( 'items.code', 'LIKE', '%' . $q . '%' )
            ->orWhere('categories.name', 'LIKE', '%' . $q . '%')
            ->select( 'items.name', 'items.id', 'items.category_id', 'items.code' )->paginate(10)->setPath('');
    
            $items->appends ( array (
                        'q' => $q
                ) );
    
            return response()->json($items);

        }else{

            $items = Item::with('category')->orderBy('name', 'asc')->paginate(10);
            
            return response()->json($items);
        }
       
    }

    public function trashbin(){

        return view('items.trash');
    }

    public function getSoftDeleteItems(){
        $trashedItems = Item::with('category')->onlyTrashed()->paginate(10);
        return response()->json($trashedItems);
    }

    public function restore($itemID){
        Item::onlyTrashed()->find($itemID)->restore($itemID);
    }

    public function permanentlyDelete($itemID){

        try{
            Item::onlyTrashed()->find($itemID)->forceDelete();
        }catch(\Exception $e){
          return  ['status' => 409, 'errMsg' => "item is already used in other transaction."];
        }

    }
}
