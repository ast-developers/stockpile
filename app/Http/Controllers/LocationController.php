<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use session;

class LocationController extends Controller
{
    public function __construct(){
     /**
     * Set the database connection. reference app\helper.php
     */     
          //selectDatabase();
    }
    /**
     * Display a listing of the Locations.
     *
     * @return Location list page view
     */
    public function index()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['list_menu'] = 'location';
        $data['locationData'] = DB::table('location')->orderBy('id', 'desc')->get();
        
        return view('admin.location.location_list', $data);
    }

    /**
     * Show the form for creating a new Location.
     *
     * @return Location create page view
     */
    public function create()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['list_menu'] = 'location';
        
        return view('admin.location.location_add', $data);
    }

    /**
     * Store a newly created Location in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Location list page view
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'location_name' => 'required|min:3',
            'loc_code' => 'required',
            'delivery_address' => 'required',
        ]);

        $data['location_name'] = $request->location_name;
        $data['loc_code'] = $request->loc_code;
        $data['delivery_address'] = $request->delivery_address;
        $data['phone'] = $request->phone;
        $data['fax'] = $request->fax;
        $data['email'] = $request->email;
        $data['contact'] = $request->contact;
        $data['created_at'] = date('Y-m-d H:i:s');

        $id = DB::table('location')->insertGetId($data);

        if (!empty($id)) {

            \Session::flash('success',trans('message.success.save_success'));
            return redirect()->intended('location');
        } else {

            return back()->withInput()->withErrors(['email' => "Invalid Request !"]);
        }
    }

    /**
     * Display the specified Location.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified Location.
     *
     * @param  int  $id
     * @return Location edit page view
     */
    public function edit($id)
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['header'] = 'location';
        $data['list_menu'] = 'location';
        $data['breadcrumb'] = 'editlocation';
        $data['locationData'] = DB::table('location')->where('id', $id)->first();

        return view('admin.location.location_edit', $data);
    }

    /**
     * Update the specified Location in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect Location list page view
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'location_name' => 'required|min:3',
            'loc_code' => 'required',
            'delivery_address' => 'required',
        ]);
        
        $data['location_name'] = $request->location_name;
        $data['loc_code'] = $request->loc_code;
        $data['delivery_address'] = $request->delivery_address;
        $data['inactive'] = $request->default;
        $data['phone'] = $request->phone;
        $data['fax'] = $request->fax;
        $data['email'] = $request->email;
        $data['contact'] = $request->contact;
        $data['updated_at'] = date('Y-m-d H:i:s');

        if ($request->default == 1) {
            \DB::table('location')->update(['inactive' => 0]);
            \DB::table('location')->where('id', $id)->update(['inactive' => 1]);
        }

        DB::table('location')->where('id', $id)->update($data);

        \Session::flash('success',trans('message.success.update_success'));
            return redirect()->intended('location');
    }

    /**
     * Remove the specified Location from storage.
     *
     * @param  int  $id
     * @return redirect Location list page view
     */
    public function destroy($id)
    {
        if (isset($id)) {
            $record = \DB::table('location')->where('id', $id)->first();
            if ($record) {
                
                DB::table('location')->where('id', '=', $id)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('location');
            }
        }
    }

    /**
     * Location validate from storage.
     *
     * @return true or false
     */

    public function validLocCode()
    {
        $loc_code = $_GET['loc_code'];
        $v = DB::table('location')->where('loc_code',$loc_code)->first();
        
        if (!empty($v)) {
             
            echo json_encode('That Location Code is already taken');
        } else {
            echo "true";
        }
    }
}
