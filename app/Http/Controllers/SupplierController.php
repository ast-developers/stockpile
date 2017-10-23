<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Purchase;
use DB;
use Excel;
use Validator;
use Input;

class SupplierController extends Controller
{
    public function __construct(){
     /**
     * Set the database connection. reference app\helper.php
     */   
        //selectDatabase();
    }
    /**
     * Display a listing of the Customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['menu'] = 'supplier';
        $data['header'] = 'supplier';
        $data['breadcrumb'] = 'supplier';
        $data['supplierData'] = DB::table('suppliers')->orderBy('supplier_id', 'desc')->get();
        $data['supplierCount'] = DB::table('suppliers')->count();
        $data['supplierActive'] = DB::table('suppliers')->where('inactive', 0)->count();
        $data['supplierInActive'] = DB::table('suppliers')->where('inactive', 1)->count();
       // d($data['supplierData'],1);
        return view('admin.supplier.supplier_list', $data);
    }

    public function orderList($sid)
    {
        $data['menu'] = 'supplier';
        $data['sub_mebu'] = 'supplier';
        $data['supplierid'] = $sid;
        $data['supplierData'] = \DB::table('suppliers')->where('supplier_id', $sid)->first();
        //d($data['supplierData'],1);
        $data['purchData'] = (new Purchase)->getAllPurchOrderById($sid);
        
        return view('admin.supplier.supplier_order', $data);
    }


    /**
     * Show the form for creating a new Customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['menu'] = 'supplier';
        $data['header'] = 'supplier';
        $data['breadcrumb'] = 'addSupplier';
        $data['countries'] = DB::table('countries')->get();
        return view('admin.supplier.supplier_add', $data);
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
            'supp_name' => 'required|min:3',
            'email' => 'required',
            //'contact' => 'required',
            'city' =>'required',
            'address'=>'required',
            'city'   =>'required',
            'state'   =>'required',
            'country' =>'required'
        ]);

        $data['supp_name'] = $request->supp_name;
        $data['email'] = $request->email;
        $data['address'] = $request->address;
        $data['contact'] = $request->contact;
        $data['city'] = $request->city;
        $data['state'] = $request->state;
        $data['zipcode'] = $request->zipcode;
        $data['country'] = $request->country;

        $data['created_at'] = date('Y-m-d H:i:s');

        $id = \DB::table('suppliers')->insertGetId($data);

        if(!empty($id)){

            \Session::flash('success',trans('message.success.save_success'));
            return redirect()->intended('supplier');
        }else {

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
        $data['menu'] = 'supplier';
        $data['header'] = 'supplier';
        $data['breadcrumb'] = 'editSupplier';
        $data['supplierData'] = \DB::table('suppliers')->where('supplier_id', $id)->first();
        $data['countries'] = DB::table('countries')->get();
       // d($data['supplierData'],1);
        return view('admin.supplier.supplier_edit', $data);
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
            'supp_name' => 'required|min:3',
            //'contact' => 'required',
            'city' =>'required',
            'address'=>'required',
            'city'   =>'required',
            'state'   =>'required',
            'country' =>'required'
        ]);

        $data['supp_name'] = $request->supp_name;
        //$data['email'] = $request->email;
        $data['address'] = $request->address;
        $data['contact'] = $request->contact;
        $data['inactive'] = $request->inactive;
        $data['city'] = $request->city;
        $data['state'] = $request->state;
        $data['zipcode'] = $request->zipcode;
        $data['country'] = $request->country;

        $data['updated_at'] = date('Y-m-d H:i:s');
        //d($data,1);
        \DB::table('suppliers')->where('supplier_id', $id)->update($data);

        \Session::flash('success',trans('message.success.update_success'));
            return redirect()->intended('supplier');
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
            $record = \DB::table('suppliers')->where('supplier_id', $id)->first();
            if($record) {
                
                \DB::table('suppliers')->where('supplier_id', '=', $id)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('supplier');
            }
        }
    }

    public function downloadCsv($type)
    {
        //d('aaa',1);
        if ($type == 'csv' ) {
            $supplierdata = DB::table('suppliers')->get();
            
            foreach ($supplierdata as $key => $value) {
                $data[$key]['Supplier'] = $value->supp_name;
                $data[$key]['Email'] = $value->email;
                $data[$key]['Phone'] = $value->contact;
                $data[$key]['Street'] = $value->address;
                $data[$key]['City'] = $value->city;
                $data[$key]['State'] = $value->state;
                $data[$key]['Zipcode'] = $value->zipcode;
                $data[$key]['Country'] = $value->country;

            }
        }

        if( $type == 'sample' ) {
            $data[0]['Supplier'] = 'John De'; 
            $data[0]['Email'] = 'example@exmample.com';
            $data[0]['Phone'] = '1235678';
            $data[0]['Street'] = 'North America';
            $data[0]['City'] = 'Washington';
            $data[0]['State'] = 'North America';
            $data[0]['Zipcode'] = '1235678';
            $data[0]['Country'] = 'US';


            $type = 'csv';
        }

        return Excel::create('Suppler_sheet'.time().'', function($excel) use ($data) {
            
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
            
        })->download($type);
    }

    public function import()
    {
        $data['menu'] = 'supplier';
        $data['header'] = 'supplier';
        
        return view('admin.supplier.supplier_import', $data);
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
                //d($csv,1);

                $unMatch = [];
                $header = array("Supplier", "Email", "Phone", "Street", "City","State","Zipcode","Country");

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
                    $data[$key]['supplier_name']  = $value[0];
                    $data[$key]['supplier_email'] = $value[1];
                    $data[$key]['phone']          = $value[2];
                    $data[$key]['address']        = $value[3];
                    $data[$key]['inactive']       = 0;
                    $data[$key]['city']           = $value[4];
                    $data[$key]['state']          = $value[5];
                    $data[$key]['zipcode']        = $value[6];
                    $data[$key]['country']        = $value[7];

                }
            }

            //d($data);

            if (!empty($data)) {
                
                foreach ($data as $key => $value) {
                    
                    $supplierData[] = [
                            'supp_name' => $value['supplier_name'],
                            'email' => $value['supplier_email'],
                            'contact' => $value['phone'],
                            'address' => $value['address'],
                            'inactive' => 0,
                            'city'     =>$value['city'],
                            'state'     =>$value['state'],
                            'zipcode'   =>$value['zipcode'],
                            'country'      =>$value['country'],                
                        ];
                }

                //d($supplierData,1);
                
                if (!empty($supplierData)) {
                    DB::table('suppliers')->insert($supplierData);
                    
                    \Session::flash('success',trans('message.success.import_success'));
                    return redirect()->intended('supplier');
                }else{
                    return back()->withErrors(['email' => "Please check Your CSV file !"]);            
                }
            }
        }
        return back();
    }
}
