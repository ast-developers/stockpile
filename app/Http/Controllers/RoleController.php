<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Role;
use Validator;
use DB;
use session;

class RoleController extends Controller
{
    public function __construct(){
     /**
     * Set the database connection. reference app\helper.php
     */     
          //selectDatabase();
    }
    /**
     * Display a listing of the User Role.
     *
     * @return Role list page view
     */
    public function index()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['list_menu'] = 'role';
        $data['roleData'] = DB::table('security_role')->get();

        return view('admin.role.role', $data);
    }

    /**
     * Show the form for creating a new User Role.
     *
     * @return Role create page view
     */
    public function create()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['list_menu'] = 'role';

        return view('admin.role.role_add', $data);
    }

    /**
     * Store a newly created User Role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Role list page view
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'role' => 'required|min:3',
            'description' => 'required',
        ]);

        $data['role'] = $request->role;
        $data['description'] = $request->description;
        $data['sections'] = serialize($request->section);
        $data['areas'] =  serialize($request->area);
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $role_id = DB::table('security_role')->insertGetId($data);

        if (!empty($role_id)) {

            \Session::flash('success',trans('message.success.save_success'));
            return redirect()->intended('user-role');
        } else {

            return back()->withInput()->withErrors(['email' => "Invalid Request !"]);
        }

    }

    /**
     * Display the specified User Role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified User Role.
     *
     * @param  int  $id
     * @return Role edit page view
     */
    public function edit($id)
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['list_menu'] = 'role';
        
        $data['roleData'] = DB::table('security_role')->where('id', $id)->first();
//d($data['roleData'],1);
        return view('admin.role.role_edit', $data);
    }

    /**
     * Update the specified User Role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect Role list page view
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'role' => 'required|min:3',
            'description' => 'required',
        ]);

        $data['role'] = $request->role;
        $data['description'] = $request->description;
        $data['sections'] = serialize($request->section);
        $data['areas'] =  serialize($request->area);
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        DB::table('security_role')->where('id', $id)->update($data);

        \Session::flash('success',trans('message.success.update_success'));
            return redirect()->intended("edit-role/$id");
    }

    /**
     * Remove the specified User Role from storage.
     *
     * @param  int  $id
     * @return Role list page view
     */
    public function destroy($id)
    {
        if (isset($id)) {
            $record = \DB::table('security_role')->where('id', $id)->first();
            if ($record) {
                
                \DB::table('security_role')->where('id', '=', $id)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('user-role');
            }
        }
    }
}