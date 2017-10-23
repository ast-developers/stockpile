<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Purchase;
use App\Model\Orders;
use App\Model\Sales;
use App\Model\Payment;
use App\Model\Shipment;
use Auth;
use DB;
use session;

class UserController extends Controller
{
    public function __construct() {
    /**
     * Set the database connection. reference app\helper.php
     */      
          //selectDatabase();
    }
    /**
     * Display a listing of the Users.
     *
     * @return User List page view
     */
    public function index()
    {
        $id = Auth::user()->id;
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['list_menu'] = 'users';

        $role =  DB::table('security_role')->get();
        $role_name = array();
        foreach ($role as $value) {
            $role_name[$value->id] = $value->role;
        }

        $data['role_name'] = $role_name;
        $data['userData'] = DB::table('users')->orderBy('id', 'desc')->get();
        
        return view('admin.user.user_list', $data);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return User cerate page view
     */
    public function create()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['list_menu'] = 'users';

        $data['roleData'] = DB::table('security_role')->get();
        
        return view('admin.user.user_add', $data);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return User List page view
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users,email',
            'real_name' => 'required',
            'password' => 'required',
            'role_id' => 'required',
        ]);

        $data['email'] = $request->email;
        $data['user_id'] = $request->real_name;
        $data['real_name'] = $request->real_name;
        $data['password'] = \Hash::make($request->password);
        $data['role_id'] = $request->role_id;
        $data['phone'] = $request->phone;
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $id = DB::table('users')->insertGetId($data);
        if (!empty($id)) {
            \Session::flash('success',trans('message.success.save_success'));
            return redirect()->intended('users');
        }
    }

    /**
     * Display the specified User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int  $id
     * @return User edit page view
     */
    public function edit($id)
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'company';
        $data['list_menu'] = 'users';
        
        $data['roleData'] = DB::table('security_role')->get();
        $data['userData'] = DB::table('users')->where('id', '=', $id)->first();
        
        return view('admin.user.editProfile', $data);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return User List page view
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'real_name' => 'required',
            'role_id' => 'required',
        ]);


        $data['user_id'] = $request->real_name;
        $data['real_name'] = $request->real_name;
        $data['role_id'] = $request->role_id;
        $data['phone'] = $request->phone;
        $data['updated_at'] = date('Y-m-d H:i:s');


        $pic = $request->file('picture');

        if (isset($pic)) {
          $upload = 'public/uploads/userPic';

          $pic1 = $request->pic;
          if ($pic1 != NULL) {
            $dir = public_path("uploads/userPic/$pic1");
            if (file_exists($dir)) {
               unlink($dir);  
            }
          }

          $filename = $pic->getClientOriginalName();  
          $pic = $pic->move($upload, $filename);
          $data['picture'] = $filename;
        }
        
        DB::table('users')->where('id', $id)->update($data);
        
            \Session::flash('success',trans('message.success.update_success'));
            return redirect()->intended("edit-user/$id");
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int  $id
     * @return User List page view
     */
    public function destroy($id)
    {
        if(isset($id)) {
            $record = \DB::table('users')->where('id', $id)->first();
            if($record) {
                
                \DB::table('users')->where('id', '=', $id)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('users');
            }
        }
    }

    /**
     * Validate email address while creating a new User.
     *
     * @return true or false
     */
    public function validEmail()
    {
        $email = $_POST['email'];
        $v = DB::table('users')->where('email', '=', $email)->first();
        if (!empty($v)) {
             echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * Show and manage user profile CRUD opration 
     *
     * @return User profile page view
     */
    public function profile()
    {
        $id = Auth::user()->id;
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'profile';
        $data['header'] = 'profile';
        $data['breadcrumb'] = 'profile';
        $data['userData'] = DB::table('users')->where('id', '=', $id)->first();
        $data['roleData'] = DB::table('security_role')->get();

        return view('admin.user.editProfile', $data);
    }

    /**
     * show user change password operation
     *
     * @return change password page view
     */
    public function changePassword($id)
    {
        $data['menu'] = 'NULL';
        $data['header'] = 'profile';
        $data['breadcrumb'] = 'change/password';
        $data['userData'] = DB::table('users')->where('id', '=', $id)->first();

        return view('admin.user.change_password', $data);
    }

    /**
     * Change user password operation perform
     *
     * @return change password page view
     */

    public function updatePassword(Request $request, $id)
    {
         $this->validate($request, [
            'old_pass' => 'required',
            'new_pass' => 'required',
        ]);


        $v = DB::table('users')->where('id', '=', $id)->first();
        
        $data['password'] = \Hash::make($request->new_pass);
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        if (\Hash::check($request->old_pass, $v->password)) {
            DB::table('users')->where('id', $id)->update($data);
            \Session::flash('success','Password Update successfully !');
                return redirect()->intended("change-password/$id");
        } else {

            return back()->withInput()->withErrors(['email' => "Old Password is Wrong !"]);
        }

    }

    public function userPurchaseOrderList($id){
        
        $data['menu'] = 'report';
        $data['sub_menu'] = 'member-report';
        $data['po_status'] = 'active'; 
        $data['user_id'] = $id;
        $data['supplier'] = $supplier = 'all';
        $data['location'] = $location = 'all';

       $fromDate = DB::table('purch_orders')->select('ord_date')->where('person_id',$id)->orderBy('ord_date','asc')->first();
       $toDate = DB::table('purch_orders')->select('ord_date')->where('person_id',$id)->orderBy('ord_date','desc')->first();

        $data['from'] = $from = isset($fromDate->ord_date) ? formatDate($fromDate->ord_date): formatDate(date("d-m-Y", strtotime("-1 months")));
        $data['to'] = $to = isset($toDate->ord_date) ? formatDate($toDate->ord_date) : formatDate(date('d-m-Y'));

        
        $data['user'] = DB::table('users')->where('id',$id)->first();

        $data['supplierList'] = DB::table('suppliers')
                                ->select('supplier_id','supp_name')
                                ->get();

        $data['locationList'] = DB::table('location')
                                ->select('loc_code','location_name')
                                ->get();
        $data['purchData'] = (new Purchase)->getAllPurchOrderByUserId($from, $to, $supplier, $location, $id);
        
        if(isset($_GET['btn'])){
        
            $data['supplier'] = $supplier = $_GET['supplier'];
            $data['location'] = $location = $_GET['location'];
            $data['from'] = $from = $_GET['from'];
            $data['to'] = $to = $_GET['to'];

            $data['purchData'] = (new Purchase)->getAllPurchOrderByUserId($from, $to, $supplier, $location, $id);
        }

        return view('admin.userdetail.po-orders', $data);
    }

    public function userSalesOrderList($id){
        
        $data['so_status'] = 'active';
        $data['menu'] = 'report';
        $data['sub_menu'] = 'member-report';

        $data['customer'] = $customer = 'all';
        $data['location'] = $location = 'all';
        $data['user_id'] = $id;

        $fromDate = DB::table('sales_orders')->select('ord_date')->where('person_id',$id)->orderBy('ord_date','asc')->first();
        $data['from'] = $from = isset($fromDate->ord_date) ? formatDate($fromDate->ord_date): formatDate(date("d-m-Y", strtotime("-1 months")));
        $data['to'] = $to = formatDate(date('d-m-Y'));

        $data['user'] = DB::table('users')->where('id',$id)->first();
        
        $data['customerList'] = DB::table('debtors_master')
                                ->select('debtor_no','name')
                                ->get();

        $data['locationList'] = DB::table('location')
                                ->select('loc_code','location_name')
                                ->get();

        $data['salesData'] = (new Orders)->getAllSalseOrderByUserId($from, $to, $customer, $location, $id);

        if(isset($_GET['btn'])){
        
            $data['customer'] = $customer = $_GET['customer'];
            $data['location'] = $location = $_GET['location'];
            $data['from'] = $from = $_GET['from'];
            $data['to'] = $to = $_GET['to'];

            $data['salesData'] = (new Orders)->getAllSalseOrderByUserId($from, $to, $customer, $location, $id);
        }        

        return view('admin.userdetail.so-orders', $data);

    }

    public function userSalesInvoiceList($id){
        
        $data['invoice'] = 'active';
        $data['menu'] = 'report';
        $data['sub_menu'] = 'member-report';
        $data['user_id'] = $id;

        $data['customer'] = $customer = 'all';
        $data['location'] = $location = 'all';
        
        $data['user'] = DB::table('users')->where('id',$id)->first();

        $fromDate = DB::table('sales_orders')->select('ord_date')->where('person_id',$id)->orderBy('ord_date','asc')->first();
        $data['from'] = $from = isset($fromDate->ord_date) ? formatDate($fromDate->ord_date): formatDate(date("d-m-Y", strtotime("-1 months")));
        $data['to'] = $to = formatDate(date('d-m-Y'));

        $data['customerList'] = DB::table('debtors_master')
                                ->select('debtor_no','name')
                                ->get();

        $data['locationList'] = DB::table('location')
                                ->select('loc_code','location_name')
                                ->get();

        $data['salesData'] = (new Sales)->getAllSalseInvoiceByUserId($from, $to, $customer, $location, $id);

        if(isset($_GET['btn'])){
        
            $data['customer'] = $customer = $_GET['customer'];
            $data['location'] = $location = $_GET['location'];
            $data['from'] = $from = $_GET['from'];
            $data['to'] = $to = $_GET['to'];

            $data['salesData'] = (new Sales)->getAllSalseInvoiceByUserId($from, $to, $customer, $location, $id);
        }

        
        return view('admin.userdetail.invoices', $data);
        
    }

    public function userTransferList($id){
        
        $data['transfer'] = 'active';
        $data['menu'] = 'report';
        $data['sub_menu'] = 'member-report';
        $data['user_id'] = $id;
        $data['source'] = $source = 'all';
        $data['destination'] = $destination = 'all';

        $data['user'] = DB::table('users')->where('id',$id)->first();

        $fromDate = DB::table('stock_transfer')->select('transfer_date')->where('person_id',$id)->orderBy('transfer_date','asc')->first();

        $data['from'] = $from = isset($fromDate->transfer_date) ? formatDate($fromDate->transfer_date): formatDate(date("d-m-Y", strtotime("-1 months")));
        $data['to'] = $to = formatDate(date('d-m-Y'));

        $data['locationList'] = DB::table('location')
                                ->select('loc_code','location_name')
                                ->get();

        $data['list'] = (new Shipment)->getAllStockTransferByUserId($from, $to, $source, $destination, $id);
        
        //d($data,1);
        if(isset($_GET['btn'])){

            $data['source'] = $source = $_GET['source'];
            $data['destination'] = $destination = $_GET['destination'];
            $data['from'] = $from = $_GET['from'];
            $data['to'] = $to = $_GET['to'];
            $data['list'] = (new Shipment)->getAllStockTransferByUserId($from, $to, $source, $destination, $id);
        }

        return view('admin.userdetail.transfer', $data);
        
    }

    public function userPaymentList($id){
        
        $data['payment'] = 'active';
        $data['menu'] = 'report';
        $data['sub_menu'] = 'member-report';
        $data['user_id'] = $id;
        $data['customer'] = $customer = 'all';
        $data['user'] = DB::table('users')->where('id',$id)->first();
        
        $fromDate = DB::table('payment_history')->select('payment_date')->where('person_id',$id)->orderBy('payment_date','asc')->first();

        $data['from'] = $from = isset($fromDate->payment_date) ? formatDate($fromDate->payment_date): formatDate(date("d-m-Y", strtotime("-1 months")));

        $data['to'] = $to = formatDate(date('d-m-Y'));

        $data['customerList'] = DB::table('debtors_master')
                                ->select('debtor_no','name')
                                ->get();

        $data['paymentList'] = (new Payment)->getAllPaymentByUserId($from, $to, $customer, $id);
        
        if(isset($_GET['btn'])){
        
            $data['customer'] = $customer = $_GET['customer'];
            $data['from'] = $from = $_GET['from'];
            $data['to'] = $to = $_GET['to'];

            $data['paymentList'] = (new Payment)->getAllPaymentByUserId($from, $to, $customer, $id);
        }

        return view('admin.userdetail.payments', $data);
        
    }

    /**
     * Display a listing of the Users.
     *
     * @return User List page view
     */
    public function memberReport()
    {
        $id = Auth::user()->id;
        $data['menu'] = 'report';
        $data['sub_menu'] = 'member-report';

        $role =  DB::table('security_role')->get();
        $role_name = array();
        foreach ($role as $value) {
            $role_name[$value->id] = $value->role;
        }

        $data['role_name'] = $role_name;
        $data['userData'] = DB::table('users')->orderBy('id', 'desc')->get();
        
        return view('admin.user.team_member', $data);
    }



}
