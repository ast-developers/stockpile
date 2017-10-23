<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use PDF;

class StockTransferController extends Controller
{
    public function __construct(){
     /**
     * Set the database connection. reference app\helper.php
     */   
        //selectDatabase();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['menu'] = 'transfer';
        $data['sub_menu'] = 'transfer/list';

        $data['list'] = DB::table('stock_transfer')
                        ->orderBy('transfer_date','DESC')
                        ->get();  
        return view('admin.transfer.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['menu'] = 'transfer';
        $data['sub_menu'] = 'transfer/create';
        $data['locationList'] = DB::table('location')->get();       
        return view('admin.transfer.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     **/
    public function store(Request $request)
    {
        $userId = \Auth::user()->id;
        $this->validate($request, [
            'source' => 'required',
            'transfer_date' => 'required',
            'destination' => 'required',
        ]);
        
        $itemQuantity = $request->quantity;        
        $itemIds = $request->id;
        $stockIds = $request->stock;
        $description = $request->description;
        $transfer_date = DbDateFormat($request->transfer_date);
        $total = 0;

        foreach ($itemQuantity as $index => $qty) {
            $total += $qty;
        }

        $transferInfo['source'] = $request->source;
        $transferInfo['destination'] = $request->destination;
        $transferInfo['person_id'] = $userId;
        $transferInfo['transfer_date'] = $transfer_date;
        $transferInfo['note'] = $request->comments;
        $transferInfo['qty'] = $total;
        
        $transfer_id = DB::table('stock_transfer')->insertGetId($transferInfo);

        foreach ($stockIds as $key => $stock) {
           // Store In
           $stockIn[$key]['stock_id'] = $stock;
           $stockIn[$key]['trans_type'] = STOCKMOVEIN;
           $stockIn[$key]['loc_code'] = $request->destination;
           $stockIn[$key]['tran_date'] = $transfer_date;
           $stockIn[$key]['person_id'] = $userId;
           $stockIn[$key]['transfer_id'] = $transfer_id;
           $stockIn[$key]['reference'] = 'moved_from_'.$request->source;
           $stockIn[$key]['transaction_reference_id'] = $transfer_id;
           $stockIn[$key]['qty'] = $itemQuantity[$key];
           $stockIn[$key]['note'] = $request->comments;  

           // Store Out
           $stockOut[$key]['stock_id'] = $stock;
           $stockOut[$key]['trans_type'] = STOCKMOVEOUT;
           $stockOut[$key]['loc_code'] = $request->source;
           $stockOut[$key]['tran_date'] = $transfer_date;
           $stockOut[$key]['person_id'] = $userId;
           $stockOut[$key]['reference'] = 'moved_in_'.$request->destination;
           $stockOut[$key]['transaction_reference_id'] = $transfer_id;
           $stockOut[$key]['qty'] = '-'.$itemQuantity[$key];
           $stockOut[$key]['note'] = $request->comments;  
        }

        for ($i=0; $i < count($stockIds); $i++) { 
            DB::table('stock_moves')->insertGetId($stockIn[$i]);
            DB::table('stock_moves')->insertGetId($stockOut[$i]);
        }

        \Session::flash('success',trans('message.success.save_success'));
        return redirect()->intended('transfer/list');

    }
    public function itemSearch(Request $request)
    {
            $data = array();
            $data['status_no'] = 0;
            $data['message']   ='No Item Found!';
            $data['items'] = array();

            $item = DB::table('item_code')
                  ->where('item_code.description','LIKE','%'.$request->search.'%')
                  ->where(['item_code.inactive'=>0,'item_code.deleted_status'=>0])
                  ->select('item_code.id','item_code.stock_id','item_code.description')
                  ->get();
            if($item){
                $data['status_no'] = 1;
                $data['message']   ='Item Found';

                $i = 0;
                foreach ($item as $key => $value) {
                    $return_arr[$i]['id'] = $value->id;
                    $return_arr[$i]['stock_id'] = $value->stock_id;
                    $return_arr[$i]['description'] = $value->description;
                    $i++;
                }
                $data['items'] = $return_arr;
            }
            echo json_encode($data);
            exit;

    }

    public function checkItemQty(Request $request)
    {
        $data = array();
        $location = $request['source'];
        $stock_id = $request['stock_id'];
       // d($request->all());
        $result = DB::table('stock_moves')
                     ->select(DB::raw('sum(qty) as total'))
                     ->where(['stock_id'=>$stock_id, 'loc_code'=>$location])
                     ->groupBy('loc_code')
                     ->first();
              
        if(isset($result)){
            $data['qty'] = $result->total;
            $data['status_no'] = 1;
        }else{
            $data['qty'] = 0;
            $data['status_no'] = 0;  
        }
        $data['message'] = 'Available quantity is '.$data['qty'];
        //d($data,1);
        return json_encode($data);        

    }

    public function destinationList(Request $request){
        $source = $request['source'];
        $data['status_no'] = 0;
        $destination = '';
        $result = DB::table('location')->select('loc_code','location_name')->where('loc_code','!=',$source)->orderBy('location_name','ASC')->get();
        //d($result,1);
        if(!empty($result)){
            $data['status_no'] = 1;
            $destination .= "<option value=''>Select One</option>";
            foreach ($result as $key => $value) {
            $destination .= "<option value='".$value->loc_code."'>".$value->location_name."</option>";  
        }
        $data['destination'] = $destination; 
       }
        return json_encode($data);
    }

    public function details($id)
    {
        $data['menu'] = 'transfer';
        $data['sub_menu'] = 'transfer/create';
        
        $data['Info'] = DB::table('stock_transfer')
                              ->where('id','=',$id)
                              ->first(); 

        $data['List'] = DB::table('stock_moves')
                              ->where('transfer_id','=',$id)
                              ->orderBy('qty','DESC')
                              ->get();   
        //d($data['Info'],1);    
        return view('admin.transfer.detail', $data);
    }

    public function destroy($id)
    {
        if(isset($id)) {
            $record = \DB::table('stock_transfer')->where('id', $id)->first();
            if($record) {               
                DB::table('stock_moves')->where(['transaction_reference_id'=>$id,'reference'=>'moved_in_'.$record->destination])->delete();
                DB::table('stock_moves')->where(['transaction_reference_id'=>$id,'reference'=>'moved_from_'.$record->source])->delete();
                DB::table('stock_transfer')->where(['id'=>$id])->delete();
                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('transfer/list');
            }
        }
    }

}
