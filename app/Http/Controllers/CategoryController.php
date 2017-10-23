<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Category;
use session;
use Input;
use Excel;
use DB;
use Validator;

class CategoryController extends Controller
{
    public function __construct(){
     /**
     * Set the database connection. reference app\helper.php
     */   
        //selectDatabase();
    }
    /**
     * Display a listing of the Category.
     *
     * @return Category List page view
     */
    public function index()
    {
        $data['access'] = \Session::all();
        //d($data['access'],1);
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['list_menu'] = 'category';
        
        $data['categoryData'] = (new Category)->getAllCategory();
        $data['unitData'] = (new Category)->getAllUnits();
        
        return view('admin.category.category_list', $data);
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Category create page view
     */
    public function create()
    {
        $data['menu'] = 'category';
        
        $data['unitData'] = (new Category)->getAllUnits();
        
        return view('admin.category.category_add', $data);
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Category List page view
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|min:3',
            'dflt_units' => 'required',
        ]);

        $data['dflt_units'] = $request->dflt_units;
        $data['description'] = $request->description;
        $data['created_at'] = date('Y-m-d H:i:s');

        $id = (new Category)->createCategory($data);

        if (!empty($id)){

            \Session::flash('success',trans('message.success.save_success'));
            return redirect()->intended('item-category');
        } else {

            return back()->withInput()->withErrors(['email' => "Invalid Request !"]);
        }
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
     * Show the form for editing the specified Category.
     *
     * @param  int  $id
     * @return Category edit page view
     */
    public function edit()
    {
        $id = $_POST['id'];

        $categoryData = (new Category)->getById($id);

        $return_arr['name'] = $categoryData->description;
        $return_arr['dflt_units'] = $categoryData->dflt_units;
        $return_arr['category_id'] = $categoryData->category_id;

        echo json_encode($return_arr);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect Category List page view
     */
    public function update(Request $request)
    {    
        $this->validate($request, [
            'description' => 'required|min:3',
            'dflt_units' => 'required',
            'cat_id' => 'required',
        ]);

        $id = $request->cat_id;
        $data['dflt_units'] = $request->dflt_units;
        $data['description'] = $request->description;
        $data['updated_at'] = date('Y-m-d H:i:s');

        $id = (new Category)->updateCategory($id, $data);

        \Session::flash('success',trans('message.success.update_success'));
            return redirect()->intended('item-category');
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param  int  $id
     * @return redirect Category List page view
     */
    public function destroy($id)
    {
        if (isset($id)) {
            $record = \DB::table('stock_category')->where('category_id', $id)->first();
            if ($record) {
                
                \DB::table('stock_category')->where('category_id', '=', $id)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('item-category');
            }
        }
    }

    public function downloadCsv($type)
    {
        if ($type == 'csv' ) {
            $categorydata = (new Category)->getAllCategory();
            foreach ($categorydata as $key => $value) {
                $data[$key]['Category Name'] = $value->description;
                $data[$key]['Unit'] = $value->name;
            }
        }

        if( $type == 'sample' ) {
            $data[0]['Category Name'] = 'Sample Data'; 
            $data[0]['Unit'] = 'Sample Data';
            $data[1]['Category Name'] = 'Sample Data'; 
            $data[1]['Unit'] = 'Sample Data';

            $type = 'csv';
        }

        return Excel::create('Category_sheet'.time().'', function($excel) use ($data) {
            
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
            
        })->download($type);
    }

    public function import()
    {
        $data['menu'] = 'category';
        $data['header'] = 'category';
        $data['breadcrumb'] = 'addCategory';
        
        return view('admin.category.category_import', $data);
    }

    public function importCsv(Request $request)
    {
        $file = $request->file('import_file');
        
        $validator = Validator::make(
            [
                'file'      => $file,
                'extension' => strtolower($file->getClientOriginalExtension()),
            ],
            [
                'file'          => 'required',
                'extension'      => 'required|in:csv',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors(['email' => "File type Invalid !"]);
        }

        if (Input::hasFile('import_file')) {
                $path = Input::file('import_file')->getRealPath();
                $data = Excel::load($path, function($reader) {
            })->get();

            $unit_id = array();
            $unit =  DB::table('item_unit')->get();

            foreach ($unit as $value) {
                $unit_id[$value->name] = $value->id;
            }

            
            if (!empty($data) && $data->count()) {

                foreach ($data as $key => $value) {
                    $cat[] = ['description' => $value->category_name, 'dflt_units' => $value->unit];
                }
                $input = array_map("unserialize", array_unique(array_map("serialize", $cat)));
                sort($input);
                
                foreach ($input as $key => $value) {
                    
                    $unitCount = DB::table('item_unit')->where('name','=',$value['dflt_units'])->count();
                    $catNameCount = DB::table('stock_category')->where('description','=',$value['description'])->count();
                    
                    if ($unitCount > 0 && $catNameCount <= 0) {

                        $insert[] = ['description' => $value['description'], 'dflt_units' => $unit_id[$value['dflt_units']]];
                    }
                }
                
                if (!empty($insert)) {
                    DB::table('stock_category')->insert($insert);
                    
                    \Session::flash('success',trans('message.success.save_success'));
                    return redirect()->intended('item-category');
                }
            }
        }
        return back();
    }
}
