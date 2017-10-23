<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use session;

class UnitController extends Controller
{
    public function __construct(){
    /**
     * Set the database connection. reference app\helper.php
     */    
        //selectDatabase();
    }
    /**
     * Display a listing of the Item Units.
     *
     * @return Unit list page view
     */
    public function index()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['list_menu'] = 'unit';
        $data['unitData'] = DB::table('item_unit')->get();
        
        return view('admin.unit.unit_list', $data);
    }


    /**
     * Show the form for creating a new Item Unit.
     *
     * @return Unit create page view
     */
    public function create()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'unit';
        $data['header'] = 'unit';
        $data['breadcrumb'] = 'addUnit';
        return view('admin.unit.unit_add', $data);
    }

    /**
     * Store a newly created Item Unit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Unit list page view
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'abbr' => 'required',
        ]);

        $data['name'] = $request->name;
        $data['abbr'] = $request->abbr;
        $data['created_at'] = date('Y-m-d H:i:s');

        $id = \DB::table('item_unit')->insertGetId($data);

        if (!empty($id)) {

            \Session::flash('success',trans('message.success.save_success'));
            return redirect()->intended('unit');
        } else {

            return back()->withInput()->withErrors(['email' => "Invalid Request !"]);
        }
    }

    /**
     * Display the specified Item Unit.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified Item Unit.
     *
     * @param  int  $id
     * @return Unit edit page view
     */
    public function edit()
    {

        $id = $_POST['id'];

        $unitData = \DB::table('item_unit')->where('id', $id)->first();
        
        $return_arr['name'] = $unitData->name;
        $return_arr['abbr'] = $unitData->abbr;
        $return_arr['id'] = $unitData->id;

        echo json_encode($return_arr);
    }

    /**
     * Update the specified Item Unit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect Unit list page view
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'abbr' => 'required',
            'id' => 'required',
        ]);

        $id = $request->id;
        $data['name'] = $request->name;
        $data['abbr'] = $request->abbr;
        $data['updated_at'] = date('Y-m-d H:i:s');

        \DB::table('item_unit')->where('id', $id)->update($data);

        \Session::flash('success',trans('message.success.update_success'));
            return redirect()->intended('unit');
    }

    /**
     * Remove the specified Item Unit from storage.
     *
     * @param  int  $id
     * @return Unit list page view
     */
    public function destroy($id)
    {
        if (isset($id)) {
            $record = \DB::table('item_unit')->where('id', $id)->first();
            if ($record) {
                
                \DB::table('item_unit')->where('id', '=', $id)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('unit');
            }
        }
    }
}
