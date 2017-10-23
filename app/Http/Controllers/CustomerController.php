<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Orders;
use DB;
use Input;
use Excel;
use Validator;
use Session;
use URL;

class CustomerController extends Controller
{
    public function __construct(Orders $orders){
     /**
     * Set the database connection. reference app\helper.php
     */   
        //selectDatabase();
        $this->order = $orders;
    }
    /**
     * Display a listing of the Customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['menu'] = 'customer';
        $data['customerData'] = DB::table('debtors_master')->orderBy('debtor_no', 'desc')->get();
        $data['totalBranch'] = DB::table('cust_branch')->count();
        $data['customerCount'] = DB::table('debtors_master')->count();
        $data['customerActive'] = DB::table('debtors_master')->where('inactive', 0)->count();
        $data['customerInActive'] = DB::table('debtors_master')->where('inactive', 1)->count();
        //d($data['cusBranchData'],1);
        return view('admin.customer.customer_list', $data);
    }

    /**
     * Show the form for creating a new Customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['menu'] = 'customer';
        $data['countries'] = DB::table('countries')->get();
        $data['sales_types'] = DB::table('sales_types')->get();
        return view('admin.customer.customer_add', $data);
    }

    /**
     * Store a newly created Customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:debtors_master,email',
            'bill_street'=>'required',
            'bill_city'=>'required',
            'bill_state'=>'required',
            'bill_country_id'=>'required',
            'ship_street'=>'required',
            'ship_city'=>'required',
            'ship_state'=>'required',
            'ship_country_id'=>'required'
        ]);
        
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        //$data['sales_type'] = $request->sales_type;
        $data['created_at'] = date('Y-m-d H:i:s');

        $id = DB::table('debtors_master')->insertGetId($data);

        if(!empty($id)) {

            $data2['debtor_no'] = $id;
            $data2['br_name'] = $request->name;
            $data2['billing_street'] = $request->bill_street;
            $data2['billing_city'] = $request->bill_city;
            $data2['billing_state'] = $request->bill_state;
            $data2['billing_zip_code'] = $request->bill_zipCode;
            $data2['billing_country_id'] = $request->bill_country_id;
            $data2['shipping_street'] = $request->ship_street;
            $data2['shipping_city'] = $request->ship_city;
            $data2['shipping_state'] = $request->ship_state;
            $data2['shipping_zip_code'] = $request->ship_zipCode;
            $data2['shipping_country_id'] = $request->ship_country_id;

            DB::table('cust_branch')->insert($data2);

            \Session::flash('success',trans('message.success.save_success'));
            return redirect()->intended("customer/edit/$id");
        } else {

            return back()->withInput()->withErrors(['email' => "Invalid Request !"]);
        }
    }

    /**
     * Display the specified Customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified Customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['menu'] = 'customer';
        $data['sub_menu'] = 'profile';
        $data['debtor_no'] = $id;
        $data['customerData'] = DB::table('debtors_master')->where('debtor_no', $id)->first();
        $data['cusBranchData'] = DB::table('cust_branch')->where('debtor_no',$id)->get();
        $data['saleTypeData'] = DB::table('sales_types')->get();
        $data['countries'] = DB::table('countries')->get();
        $data['status_tab'] = 'active';
     //   d($data['cusBranchData'],1);
        return view('admin.customer.customer_edit', $data);
    }

    public function salesOrder($id)
    {
        $data['menu'] = 'customer';
        $data['sub_menu'] = 'sale-order';
        $data['customerData'] = DB::table('debtors_master')->where('debtor_no', $id)->first();
        $data['salesOrderData'] = $this->order->getAllSalseOrderByCustomer($id);
        return view('admin.customer.customer_sales_order', $data);
    }

    public function invoice($id)
    {
        $data['menu'] = 'customer';
        $data['sub_menu'] = 'invoice';
        $data['customerData'] = DB::table('debtors_master')->where('debtor_no', $id)->first();
        $data['salesOrderData'] = $this->order->getAllSalseInvoiceByCustomer($id);
       // d($data['salesOrderData'],1);
        return view('admin.customer.customer_invoice', $data);
    }

    public function payment($id)
    {
        $data['menu'] = 'customer';
        $data['sub_menu'] = 'payment';
        $data['customerData'] = DB::table('debtors_master')->where('debtor_no', $id)->first();
        $data['paymentList'] = DB::table('payment_history')
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','payment_history.customer_id')
                             ->leftjoin('payment_terms','payment_terms.id','=','payment_history.payment_type_id')
                             ->leftjoin('sales_orders','sales_orders.reference','=','payment_history.invoice_reference')
                             ->where('sales_orders.debtor_no',$id)
                             ->select('payment_history.*','debtors_master.name','payment_terms.name as pay_type','sales_orders.order_no as invoice_id','sales_orders.order_reference_id as order_id')
                             ->orderBy('payment_date','DESC')
                             ->get();
        //d($data['paymentList'],1);

        return view('admin.customer.customer_payment', $data);
    }

    public function shipment($id)
    {
        $data['menu'] = 'customer';
        $data['sub_menu'] = 'delivery';
        $data['customerData'] = DB::table('debtors_master')->where('debtor_no', $id)->first();
        //$data['salesOrderData'] = $this->order->getAllSalseInvoiceByCustomer($id);
        $data['shipmentList'] = DB::table('shipment')
                ->leftJoin('shipment_details', 'shipment.id', '=', 'shipment_details.shipment_id')
                ->leftJoin('sales_orders','sales_orders.order_no','=','shipment_details.order_no')
                ->leftJoin('debtors_master','debtors_master.debtor_no','=','sales_orders.debtor_no')
                ->where('debtors_master.debtor_no', $id)
                ->select('shipment_details.shipment_id','sales_orders.reference','sales_orders.order_no as order_id','debtors_master.name','shipment.packed_date','shipment.status', DB::raw('sum(shipment_details.quantity) as total_shipment'))
                ->groupBy('shipment_details.shipment_id')
                ->orderBy('shipment.packed_date','DESC')
                ->get();
        //d($data,1);

        return view('admin.customer.customer_shipment', $data);
    }

    /**
     * Update the specified Customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required',
            'phone' => 'required',
            //'sales_type' => 'required',
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
       // $data['sales_type'] = $request->sales_type;
        $data['inactive'] = $request->inactive;
        $data['updated_at'] = date('Y-m-d H:i:s');

        DB::table('debtors_master')->where('debtor_no', $id)->update($data);

        \Session::flash('success',trans('message.success.update_success'));
        return redirect()->intended('customer/list');
    }

    public function updatePassword(Request $request)
    {
 //d($request->password,1);

       $this->validate($request, [
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required|min:5'
        ]);

        $id = $request->customer_id;

        $password = \Hash::make($request->password);
       

        DB::table('debtors_master')
        ->where('debtor_no', $id)
        ->update(['password' => $password]);

        \Session::flash('success',trans('message.success.update_success'));

        return redirect()->intended("customer/edit/$id");
    }

    /**
     * Remove the specified Customer from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(isset($id)) {
            $record = \DB::table('debtors_master')->where('debtor_no', $id)->first();
            if($record) {
                
                \DB::table('debtors_master')->where('debtor_no', '=', $id)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('customer');
            }
        }
    }

    public function downloadCsv($type)
    {
        if ($type == 'csv' ) {
            $customerdata = DB::table('debtors_master')
                              ->leftjoin('cust_branch','debtors_master.debtor_no','=','cust_branch.debtor_no')
                              ->select('debtors_master.*','cust_branch.*')
                              ->groupBy('cust_branch.debtor_no')
                              ->get();
            //d($customerdata,1);
            
            foreach ($customerdata as $key => $value) {
                $data[$key]['Customer'] = $value->name;
                $data[$key]['Email'] = $value->email;
                $data[$key]['Phone'] = $value->phone;
                $data[$key]['Branch'] = $value->br_name;
                
                $data[$key]['Billing Street'] = $value->billing_street;
                $data[$key]['Billing City'] = $value->billing_city;
                $data[$key]['Billing State'] = $value->billing_state; 
                $data[$key]['Billing Zipcode'] = trim($value->billing_zip_code);
                $data[$key]['Billing Country'] = trim($value->billing_country_id);
               
                $data[$key]['Shipping Street'] = $value->shipping_street;
                $data[$key]['Shipping City'] = $value->shipping_city;
                $data[$key]['Shipping State'] = $value->shipping_state;      
                $data[$key]['Shipping Zipcode'] = trim($value->shipping_zip_code);
                $data[$key]['Shipping Country'] = ($value->shipping_country_id);  
            }
        }

        if( $type == 'sample' ) {
            
            $data[0]['Customer'] = 'John Michel'; 
            $data[0]['Email'] = 'sample@gmail.com';
            $data[0]['Phone'] = '0123456789';
            
            $data[0]['Billing Street'] = '2430';
            $data[0]['Billing City'] = 'Washington';
            $data[0]['Billing State'] = 'Washington';
            $data[0]['Billing Zipcode'] = '1234';
            $data[0]['Billing Country'] = 'US';

            $data[0]['Shipping Street'] = '2430';
            $data[0]['Shipping City'] = 'Washington';
            $data[0]['Shipping State'] = 'Washington';
            $data[0]['Shipping Zipcode'] = '1234';  
            $data[0]['Shipping Country'] = 'US';          


            $type = 'csv';
        }

        return Excel::create('Customer_sheet'.time().'', function($excel) use ($data) {
            
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
            
        })->download($type);
    }

    public function import()
    {
        $data['menu'] = 'customer';
        $data['header'] = 'customer';
        $data['breadcrumb'] = 'importCustomer';
        
        return view('admin.customer.customer_import', $data);
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

        if($validator->fails()){
            return back()->withErrors(['email' => "File type Invalid !"]);
        }

        if (Input::hasFile('import_file')) {
                $path = Input::file('import_file')->getRealPath();
            
            $csv = array_map('str_getcsv', file($path));

            $unMatch = [];
            $header = array("Customer", "Email", "Phone", "Branch", "Billing Street","Billing City","Billing State","Billing Country","Billing Zipcode", "Shipping Street","Shipping City","Shipping State","Shipping Country","Shipping Zipcode",);

            for ($i=0; $i < sizeof($csv[0]); $i++) {
                if (! in_array($csv[0][$i], $header)) {
                    $unMatch[] = $csv[0][$i];
                }
            }

            if(!empty($unMatch)){

                return back()->withErrors(['email' => "Please Check Csv Header Name !"]);
            }
            
            $data = [];
            foreach ($csv as $key => $value) {
                if($key != 0) {
                    
                    $data[$key]['customer_name'] = $value[0];
                    $data[$key]['email'] = $value[1];
                    $data[$key]['phone'] = $value[2];

                    $data[$key]['billing_street'] = $value[3];
                    $data[$key]['billing_city'] = $value[4];
                    $data[$key]['billing_state'] = $value[5];
                    $data[$key]['billing_zip_code'] = $value[6];
                    $data[$key]['billing_country_id'] = $value[7];
                    

                    $data[$key]['shipping_street'] = $value[8];
                    $data[$key]['shipping_city'] = $value[9];
                    $data[$key]['shipping_state'] = $value[10];
                    $data[$key]['shipping_zip_code'] = $value[11]; 
                    $data[$key]['shipping_country_id'] = $value[12];
                                       

                }
            }
            //d($data,1);

            if (!empty($data) ) {

                foreach ($data as $key => $value) {
                    
                    $email = DB::table('debtors_master')->where('email','=',$value['email'])->count();
                    
                    if ($email == 0) {

                        $cusData[] = [
                                'name' => $value['customer_name'],
                                'email' => $value['email'],
                                'phone' => $value['phone'],
                            ];
                        $cusBrData[] = [
                                'br_name' => $value['customer_name'],
                                'billing_street' => $value['billing_street'],
                                'billing_city' => $value['billing_city'],
                                'billing_state' => $value['billing_state'],
                                'billing_country_id' => $value['billing_country_id'],
                                'billing_zip_code' => $value['billing_zip_code'],

                                'shipping_street' => $value['shipping_street'],
                                'shipping_city' => $value['shipping_city'],
                                'shipping_state' => $value['shipping_state'],
                                'shipping_country_id' => $value['shipping_country_id'],
                                'shipping_zip_code' => $value['shipping_zip_code'],

                            ];
                    }
                }

                
                if (!empty($cusData)) {
                   // DB::table('debtors_master')->insert($cusData);

                for ($i=0; $i < count($cusData) ; $i++) {

                     $insertid[$i]['debtor_no'] = DB::table('debtors_master')->insertGetId($cusData[$i]);
                }

                for ($j=0; $j < count($insertid) ; $j++) { 
                    $cusBrData[$j]['debtor_no'] = $insertid[$j]['debtor_no'];
                    
                }

                DB::table('cust_branch')->insert($cusBrData);

                    
                    \Session::flash('success',trans('message.success.import_success'));
                    return redirect()->intended('customer/list');
                }else{
                        return back()->withErrors(['email' => "Please Check Csv File !"]);
                    }
            }
        }
        return back();
    }

    public function storeBranch()
    {
        $data['br_name'] = $_POST['br_name'];
        $data['br_contact'] = $_POST['br_contact'];
//        $data['br_address'] = $_POST['br_address'];
        $data['billing_street'] = $_POST['bill_street'];
        $data['billing_city'] = $_POST['bill_city'];
        $data['billing_state'] = $_POST['bill_state'];
        $data['billing_zip_code'] = $_POST['bill_zipCode'];
        $data['billing_country_id'] = $_POST['bill_country_id'];
        $data['shipping_street'] = $_POST['ship_street'];
        $data['shipping_city'] = $_POST['ship_city'];
        $data['shipping_state'] = $_POST['ship_state'];
        $data['shipping_zip_code'] = $_POST['ship_zipCode'];
        $data['shipping_country_id'] = $_POST['ship_country_id'];
        $data['debtor_no'] = $_POST['cus_id'];

        $br_id = DB::table('cust_branch')->insertGetId($data);

        if($br_id) {

            $return_arr['success'] = 1;
            $return_arr['br_name'] = $data['br_name'];
            $return_arr['br_contact'] = $data['br_contact'];
            
            $return_arr['br_id'] = $br_id;
        

            echo json_encode($return_arr);
        }
    }


    public function editBranch()
    {
        $id = $_POST['id'];
        $branch = DB::table('cust_branch')->where('branch_code', $id)->first();
        
        if($branch) {
            $return_arr['br_id'] = $id;
            $return_arr['br_name'] = $branch->br_name;
            $return_arr['br_contact'] = $branch->br_contact;
           // $return_arr['br_address'] = $branch->br_address;
            
            $return_arr['billing_street'] = $branch->billing_street;
            $return_arr['billing_city'] = $branch->billing_city;
            $return_arr['billing_state'] = $branch->billing_state;
            $return_arr['billing_zip_code'] = $branch->billing_zip_code;
            $return_arr['billing_country_id'] = $branch->billing_country_id;

            $return_arr['shipping_street'] = $branch->shipping_street;
            $return_arr['shipping_city'] = $branch->shipping_city;
            $return_arr['shipping_state'] = $branch->shipping_state;
            $return_arr['shipping_zip_code'] = $branch->shipping_zip_code;
            $return_arr['shipping_country_id'] = $branch->shipping_country_id;

            echo json_encode($return_arr);
        }
    }

    public function updateBranch()
    {
        $data['br_name'] = $_POST['br_name'];
        $data['br_contact'] = $_POST['br_contact'];
        //$data['br_address'] = $_POST['br_address'];
        $data['billing_street'] = $_POST['bill_street'];
        $data['billing_city'] = $_POST['bill_city'];
        $data['billing_state'] = $_POST['bill_state'];
        $data['billing_zip_code'] = $_POST['bill_zipCode'];
        $data['billing_country_id'] = $_POST['billing_country_id'];

        $data['shipping_street'] = $_POST['ship_street'];
        $data['shipping_city'] = $_POST['ship_city'];
        $data['shipping_state'] = $_POST['ship_state'];
        $data['shipping_zip_code'] = $_POST['ship_zipCode'];
        $data['shipping_country_id'] = $_POST['shipping_country_id'];
        $br_id = $_POST['br_id'];

        DB::table('cust_branch')->where('branch_code', $br_id)->update($data);

        $return_arr['success'] = 1;
        $return_arr['br_name'] = $data['br_name'];
        $return_arr['br_contact'] = $data['br_contact'];
        //$return_arr['br_address'] = $data['br_address'];
        $return_arr['br_id'] = $br_id;
        

        echo json_encode($return_arr);
    }

    public function destroyBranch($id)
    {
        if(isset($id)) {
            $record = \DB::table('cust_branch')->where('branch_code', $id)->first();
            if($record) {
                
                DB::table('cust_branch')->where('branch_code', '=', $id)->delete();

                Session::flash('success',trans('message.success.delete_success'));
                return redirect()->back();
            }
        }
    }

    public function deleteSalesInfo(Request $request){
        $customer_id = $request['customer_id'];
        if($request['action_name'] == 'delete_order'){
            $id = $request['order_no']; 
            //d($id,1);
            if(isset($id)) {
                $record = \DB::table('sales_orders')->where('order_no', $id)->first();
                if($record) {
                    // Delete shipment information
                    DB::table('shipment')->where('order_no', '=', $record->order_no)->delete();
                    DB::table('shipment_details')->where('order_no', '=', $record->order_no)->delete();

                     // Delete Payment information
                    DB::table('payment_history')->where('order_reference', '=', $record->reference)->delete();                

                    // Delete invoice information
                    $invoice = \DB::table('sales_orders')->where('order_reference_id', $record->order_no)->first();
                    
                   // d($invoice,1);
                    DB::table('sales_orders')->where('order_reference_id', '=', $record->order_no)->delete();
                    if(!empty($invoice)){
                    DB::table('sales_order_details')->where('order_no', '=', $invoice->order_no)->delete();
                    }
                    // Delete order information
                    DB::table('sales_orders')->where('order_no', '=', $record->order_no)->delete();
                    DB::table('sales_order_details')->where('order_no', '=', $record->order_no)->delete();

                     // Delete Stock information
                    DB::table('stock_moves')->where('order_no', '=', $record->order_no)->delete();

                    \Session::flash('success',trans('message.success.delete_success'));
                    return redirect()->intended('customer/order/'.$customer_id);
                }
            }
        }elseif($request['action_name'] == 'delete_invoice'){
            $invoice_no = $request['invoice_no']; 

        if(isset($invoice_no)) {
            $record = \DB::table('sales_orders')->where('order_no', $invoice_no)->first();
            if($record) {
                
                $invoice_id = $invoice_no;
                $order_id = $record->order_reference_id;
                $invoice_reference = $record->reference;
                $order_reference = $record->order_reference;

                DB::table('sales_orders')->where('order_no', '=', $invoice_id)->delete();
                DB::table('sales_order_details')->where('order_no', '=', $invoice_id)->delete();
                DB::table('stock_moves')->where('reference', '=', 'store_out_'.$invoice_id)->delete();
                DB::table('payment_history')->where('invoice_reference', '=', $invoice_reference)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('customer/invoice/'.$customer_id);
            }
        }

        }elseif($request['action_name'] == 'delete_payment'){
           $id = $request['payment_no'];

            $paymentInfo = DB::table('payment_history')
                         ->where('id',$id)
                         ->select('id','order_reference','invoice_reference','amount')
                         ->first();
            $totalPaidAmount = DB::table('sales_orders')
                         ->where(['order_reference'=>$paymentInfo->order_reference,'reference'=>$paymentInfo->invoice_reference])
                         ->sum('paid_amount');
            $newAmount   = ($totalPaidAmount-$paymentInfo->amount);
            $update      = DB::table('sales_orders')
                         ->where(['order_reference'=>$paymentInfo->order_reference,'reference'=>$paymentInfo->invoice_reference])
                         ->update(['paid_amount'=>$newAmount]);

            DB::table('payment_history')->where('id',$id)->delete();

         \Session::flash('success',trans('message.success.delete_success'));
         return redirect()->intended('customer/payment/'.$customer_id);
        
        }elseif($request['action_name'] == 'delete_shipment'){
            $shipment_id = $request['shipment_id'];
              $shipments = DB::table('shipment')
                                     ->where('shipment.id',$shipment_id)
                                     ->leftjoin('shipment_details','shipment_details.shipment_id','=','shipment.id')
                                     ->select('shipment_details.id','shipment_details.order_no','shipment_details.stock_id','shipment_details.quantity')
                                     ->get();
              foreach ($shipments as $key => $shipment) {
                $qty = DB::table('sales_order_details')
                     ->where(['order_no'=>$shipment->order_no,'stock_id'=>$shipment->stock_id])
                     ->sum('shipment_qty');
                $newQty = ($qty-$shipment->quantity);
                $updated = DB::table('sales_order_details')
                         ->where(['order_no'=>$shipment->order_no,'stock_id'=>$shipment->stock_id])
                         ->update(['shipment_qty'=>$newQty]);
                
                DB::table('shipment_details')->where(['id'=>$shipment->id])->delete();
              }

              DB::table('shipment')->where(['id'=>$shipment_id])->delete();
              

         \Session::flash('success',trans('message.success.delete_success'));
         return redirect()->intended('customer/shipment/'.$customer_id);
        
        }
    }
}
