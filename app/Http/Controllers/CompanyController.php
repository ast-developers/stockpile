<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use session;

class CompanyController extends Controller
{
    public function __construct(){
     /**
     * Set the database connection. reference app\helper.php
     */
        //selectDatabase();
    }
    /**
     * Display a listing of the Company.
     *
     * @return Company List page view
     */
    public function index()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['list_menu'] = 'company';
        $data['companyData'] = DB::table('company')->get();
        
        return view('admin.company.company_list', $data);
    }

    public function setDatabase($host='localhost', $username='root', $password='', $database, $user_password,$email,$name)
    {

        \Config::set('database.connections.tenant.host', $host);
        \Config::set('database.connections.tenant.username', $username);
        \Config::set('database.connections.tenant.password', $password);
        \Config::set('database.connections.tenant.database', $database);

        \Config::set('database.default', 'tenant');
        $db = DB::reconnect('tenant');

        emptyDatabase();
        
        \Artisan::call('migrate');
        \Artisan::call('db:seed');

        $userData['real_name'] = $name;
        $userData['email'] = $email;
        $userData['password'] = $user_password;
        $userData['created_at'] = date('Y-m-d H:i:s');
        DB::table('users')->insert($userData);
        
    }

    /**
     * Show the form for creating a new Company.
     *
     * @return Company create page view
     */
    public function create()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['list_menu'] = 'company';
        
        return view('admin.company.company_add', $data);
    }

    /**
     * Store a newly created Company in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Company List page view
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'host' => 'required',
            'db_user' => 'required',
            'real_name' => 'required',
            'email' => 'required',
            'db_name' => 'required',
            'user_pass' => 'required',
        ]);

        $data['name'] = $request->name;
        $data['host'] = $request->host;
        $data['db_user'] = $request->db_user;
        $data['db_password'] = $request->db_password;
        $data['db_name'] = $request->db_name;
        $data['default'] = 'No';
        $user_pass = \Hash::make($request->user_pass);
        $data['created_at'] = date('Y-m-d H:i:s');

        $conn = dbConnect($request->host, $request->db_user,$request->db_password,$request->db_name);
        
        if ($conn == false) {
            return back()->withInput()->withErrors(['email' => trans('message.error.db_error')]);
            exit;
        }
        
        $database = \DB::table('company')->select('db_name')->where('db_name',$request->db_name)->first();
        if (!empty($database)) {

            return back()->withInput()->withErrors(['email' => trans('message.error.company_error')]);
            exit;
        }
        
        $id = \DB::table('company')->insertGetId($data);
        $status = $this->setDatabase($request->host, $request->db_user,$request->db_password,$request->db_name,$user_pass,$request->email,$request->real_name);

        if (!empty($id)) {

            \Session::flash('success',trans('message.success.save_success'));
            return redirect()->intended('company');
        } else {

            return back()->withInput()->withErrors(['email' => "Invalid Request !"]);
        }
    }

    /**
     * Display the specified Company.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified Company.
     *
     * @param  int  $id
     * @return Company edit page view
     */
    public function edit($id)
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['list_menu'] = 'company';
        $data['companyData'] = \DB::table('company')->where('company_id', $id)->first();

        return view('admin.company.company_edit', $data);
    }

    /**
     * Update the specified Company in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirect Company List page view
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
        ]);

        $data['name'] = $request->name;
        $data['default'] = $request->default;
        $data['updated_at'] = date('Y-m-d H:i:s');
        if ($data['default'] == 'Yes') {

            $c_id = \DB::table('company')->select('company_id')->where('default', '=', 'Yes')->first();
            
            \DB::table('company')->where('company_id', $c_id->company_id)->update(['default' => 'No']);
        }
        
        \DB::table('company')->where('company_id', $id)->update($data);

        \Session::flash('success', trans('message.success.update_success'));
            return redirect()->intended('company');
    }

    /**
     * Remove the specified Company from storage.
     *
     * @param  int  $id
     * @return redirect Company List page view
     */
    public function destroy($id)
    {
        if (isset($id)) {
            $record = \DB::table('company')->where('company_id', $id)->first();
            if ($record) {
                
                \DB::table('company')->where('company_id', '=', $id)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('company');
            }
        }
    }
}
