<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Item;
use Image;
use Session;
use Excel;
use Input;
use DB;
use Validator;
use App\Model\Report;

class ItemController extends Controller
{
    public function __construct(Report $report) {
     /**
     * Set the database connection. reference app\helper.php
     */     
      //selectDatabase();
      $this->report = $report;
    }
    /**
     * Display a listing of the Item.
     *
     * @return Item list page view
     */
    public function index()
    {
        $data['menu'] = 'item';
        $data['header'] = 'item';
        $qtyOnHand = 0;
        $costValueQtyOnHand = 0;
        $retailValueOnHand = 0;
        $profitValueOnHand = 0;
        $mac = 0;

        $data['itemData'] = (new Item)->getAllItem();
        $data['itemQuantity'] = DB::table('stock_moves')->select(DB::raw('SUM(qty) as total_item'))->first();
        $itemList = $this->report->getInventoryStockOnHand('all','all');
        //d($itemList,1);
        foreach ($itemList as $key => $item) {
            
            $qtyOnHand += $item->available_qty;
            
            if($item->received_qty !=0){
               $mac = $item->cost_amount/$item->received_qty;
            }

            $costValueQtyOnHand += $item->available_qty*$mac;
            $retailValueOnHand += $item->available_qty*$item->retail_price;
            $profitValueOnHand += (($item->available_qty*$item->retail_price)-($item->available_qty*$mac));

        }
        $data['qtyOnHand'] = $qtyOnHand;
        $data['costValueQtyOnHand'] = $costValueQtyOnHand;
        $data['retailValueOnHand'] = $retailValueOnHand;
        $data['profitValueOnHand'] = $profitValueOnHand;

        return view('admin.item.item_list', $data);
    }

    /**
     * Show the form for creating a new Item.
     *
     * @return Item create page view
     */
    public function create($tab)
    {
        $item_stock_id = Session::get('stock_id');
        $data['menu'] = 'item';

        $unit =  DB::table('item_unit')->get();
        $unit_name = array();
        foreach ($unit as $value) {
            $unit_name[$value->id] = $value->name;
        }
        
        $data['unit_name'] = $unit_name;

        $data['locData']      = DB::table('location')->get();
        $data['taxTypes']     = DB::table('item_tax_types')->get();
        $data['saleTypes']    = DB::table('sales_types')->get();
        $data['categoryData'] = DB::table('stock_category')->get();
        $data['unitData']     = DB::table('item_unit')->get();
        $data['suppliers']    = DB::table('suppliers')->get();

        if(!empty($item_stock_id)) {

            $data['stock_id'] = strtoupper($item_stock_id);
        }
        
        $data['tab'] = $tab;

        return view('admin.item.item_add', $data);
    }

    /**
     * Store a newly created Item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirection Item list page view
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'stock_id' => 'required|unique:item_code,stock_id',
            'description' => 'required',
            'tax_type_id' => 'required',
            'units' => 'required',
            'item_image' => 'mimes:jpeg,bmp,png'
        ]);

        $data['stock_id'] = strtoupper($request->stock_id);
        $data['description'] = strtoupper($request->description);
        $data['category_id'] = $request->category_id;
        $data['created_at'] = date('Y-m-d H:i:s');

        $pic = $request->file('item_image');

        if (isset($pic)) {
          $destinationPath = public_path('/uploads/itemPic/');
          $filename = $pic->getClientOriginalName();
          $img = Image::make($request->file('item_image')->getRealPath());

          $img->resize(400,400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$filename);
          
          $data['item_image'] = $filename;
        }

        $id = DB::table('item_code')->insertGetId($data);

        if (!empty($id)) {
            Session::put('stock_id', strtoupper($request->stock_id));
            
            $data2['stock_id'] = strtoupper($request->stock_id);
            $data2['description'] = $request->description;
            $data2['long_description'] = $request->long_description;
            $data2['units'] = $request->units;
            $data2['tax_type_id'] = $request->tax_type_id;
            $data2['category_id'] = $request->category_id;
            $data2['created_at'] = date('Y-m-d H:i:s');

            DB::table('stock_master')->insert($data2);

            $data3[0]['stock_id'] = strtoupper($request->stock_id);
            $data3[0]['sales_type_id'] = 1;
            $data3[0]['price'] = 0;
            $data3[0]['curr_abrev'] = 'USD';

            $data3[1]['stock_id'] = strtoupper($request->stock_id);
            $data3[1]['sales_type_id'] = 2;
            $data3[1]['price'] = 0;
            $data3[1]['curr_abrev'] = 'USD';

            DB::table('sale_prices')->insert($data3);

            $purchaseInfos['stock_id'] = strtoupper($request->stock_id);
            $purchaseInfos['price'] = 0;
            DB::table('purchase_prices')->insert($purchaseInfos);

            //\Session::flash('success',trans('message.success.save_success'));
            return redirect()->intended("edit-item/item-info/$id");

        } else {

            return back()->withInput()->withErrors(['email' => "Invalid Request !"]);
        }
    }

    /**
     * Store a newly created Item  sales price in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return redirection Item purchasing price
     */
     public function storeSalePrice(Request $request){
        $this->validate($request, [
            'stock_id' => 'required|unique:sale_prices,stock_id',
            'sales_type_id' => 'required',
            'price' => 'required|numeric'
        ]);
        $data['stock_id'] = strtoupper($request->stock_id);
        $data['sales_type_id'] = $request->sales_type_id;
        $data['price'] = $request->price;
        $data['curr_abrev'] = $request->curr_abrev;
        DB::table('sale_prices')->insert($data);
        return redirect()->intended('create-item/purchase');

     }

    /**
     * Update Item  sales price in storage.
     * @param  \Illuminate\Http\Request  $request
     * 
     */
     public function addSalePrice(Request $request) {
        $this->validate($request, [
            'stock_id' => 'required',
            'sales_type_id' => 'required',
            'price' => 'required|numeric'
        ]);

        $id = $request->item_id;

        $data['stock_id'] = $request->stock_id;
        $data['sales_type_id'] = $request->sales_type_id;
        $data['price'] = $request->price;
        $data['curr_abrev'] = $request->curr_abrev;

        $ceck = DB::table('sale_prices')->where([
                                                    ['stock_id', '=', $request->stock_id],
                                                    ['sales_type_id', '=', $request->sales_type_id],
                                                ])->first();
        if(!empty($ceck)) {
            \Session::flash('fail',"Already added !");
            return redirect()->intended("edit-item/sales-info/$id");
        }else{

            DB::table('sale_prices')->insert($data);
            \Session::flash('success',trans('message.success.update_success'));
            return redirect()->intended('edit-item/sales-info/'.$request->item_id);
        }


     }

     public function editSalePrice(Request $request) {
        //dd($request->id);
        $id = $request->id;

        $saleTypes = DB::table('sales_types')->get();

        $salesTypeName = array();
        foreach ($saleTypes as $value) {
            $salesTypeName[$value->id] = $value->sales_type;
        }

        $data = DB::table('sale_prices')->where('id', $id)->first();

        if(!empty($data)) {
            $v['id'] = $id;
            $v['sales_type_id'] = $salesTypeName[$data->sales_type_id];
            $v['price'] = $data->price;
            $v['status'] = 1;

            echo json_encode($v);
        }
     }

     public function updateSalePrice(Request $request)
     {
         $id = $request->id;

         DB::table('sale_prices')
            ->where('id', $id)
            ->update(['price' => $request->price]);

            \Session::flash('success',trans('message.success.update_success'));
            return redirect()->intended('edit-item/sales-info/'.$request->item_id);

     }

     public function deleteSalePrice($id, $item_id)
     {
         if (isset($id)) {
            $record = \DB::table('sale_prices')->where('id', $id)->first();
            if ($record) {
                
                \DB::table('sale_prices')->where('id', '=', $id)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
               return redirect()->intended('edit-item/sales-info/'.$item_id);
            }
        }
     }

    /**
     * Store a newly created Item  purchase price in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return redirection Item purchasing price
     */
     public function updatePurchasePrice(Request $request){
        $this->validate($request, [
            'stock_id' => 'required',
            'price' => 'required|numeric',
           // 'supplier_id'=>'required'
        ]);
        
        $data['price'] = $request->price;
        //$data['supplier_id'] = $request->supplier_id;
        $data['stock_id'] = $request->stock_id;
        $priceInfo = DB::table('purchase_prices')->where('stock_id',$request->stock_id)->count();
       // d($priceInfo,1);
        if($priceInfo==0){
            DB::table('purchase_prices')->insert($data);
        }else{
            DB::table('purchase_prices')->where('stock_id', $request->stock_id)->update($data);
        }
        
        \Session::flash('success',trans('message.success.update_success'));
        return redirect()->intended('edit-item/purchase-info/'.$request->item_id);
     }
    /**
     * Store a newly created Item  purchase price in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return redirection Item purchasing price
     */
     public function storePurchasePrice(Request $request){
        $this->validate($request, [
            'stock_id' => 'required|unique:purchase_prices,stock_id',
           // 'suppliers_uom' => 'required',
            'price' => 'required|numeric',
            'supplier_id'=>'required'
        ]);
        
        $data['stock_id'] = strtoupper($request->stock_id);
        $data['suppliers_uom'] = $request->suppliers_uom;
        $data['price'] = $request->price;
        $data['supplier_id'] = $request->supplier_id;
        $data['conversion_factor'] = $request->conversion_factor;
        $data['supplier_description'] = $request->supplier_description;        

        \Session::flash('success',trans('message.success.save_success'));
        DB::table('purchase_prices')->insert($data);
        return redirect()->intended('item');

     }


    /**
     * Display the specified Item.
     *
     * @param  int  $id
     * @return Item show page view
     */
    public function show($id)
    {
        $data['menu'] = 'item';
        $data['header'] = 'item';
        $data['breadcrumb'] = 'showitem';
        $loc =  DB::table('location')->get();
        $loc_name = array();
        foreach ($loc as $value) {
            $loc_name[$value->loc_code] = $value->location_name;
        }
        
        $data['loc_name'] = $loc_name;

        $data['itemData'] = (new Item)->getItemById($id);
        $data['loc_Data'] = (new Item)->getTransaction($data['itemData']->stock_id);
        $data['locData'] = DB::table('location')->get();
        $data['itemHistory'] = DB::table('stock_moves')
                                ->where(['stock_id'=>$data['itemData']->stock_id])
                                ->orderBy('trans_id', 'desc')
                                ->take(5)
                                ->get();

        
        return view('admin.item.item_show', $data);
    }

    /**
     * Show the form for editing the specified Item.
     *
     * @param  int  $id
     * @return Item edit page view
     */
    public function edit($tab,$id)
    { 
        $data['menu'] = 'item';
        $data['header'] = 'item';
        $data['breadcrumb'] = 'additem';
        $data['locData'] = DB::table('location')->get();
        $data['taxTypes'] = DB::table('item_tax_types')->get();
        $data['saleTypes'] = DB::table('sales_types')->get();
        $data['categoryData'] = DB::table('stock_category')->get();
        $data['unitData'] = DB::table('item_unit')->get();
        $data['suppliers'] = DB::table('suppliers')->get();

        $loc =  DB::table('location')->get();
        $loc_name = array();
        foreach ($loc as $value) {
            $loc_name[$value->loc_code] = $value->location_name;
        }
        
        $data['loc_name'] = $loc_name;


        $salesTypeName = array();
        foreach ($data['saleTypes'] as $value) {
            $salesTypeName[$value->id] = $value->sales_type;
        }
        
        $data['salesTypeName'] = $salesTypeName;

        $data['itemInfo'] = DB::table('item_code')
                          ->leftjoin('stock_master','stock_master.stock_id','=','item_code.stock_id')
                          ->where('item_code.id',$id)
                          ->select('item_code.*','stock_master.tax_type_id','stock_master.units','stock_master.long_description')
                          ->first();
        
        $data['salesInfo'] = DB::table('sale_prices')
                           ->where('stock_id',$data['itemInfo']->stock_id)
                           ->first();
        $data['purchaseInfo'] = DB::table('purchase_prices')
                           ->where('stock_id',$data['itemInfo']->stock_id)
                           ->first();
        //d($data['purchaseInfo'],1);
        $data['salePriceData'] = DB::table('sale_prices')->where('stock_id',$data['itemInfo']->stock_id)->get();

        $data['itemQuantity'] = DB::table('stock_moves')
                            ->select(DB::raw('SUM(qty) as total_item'), 'loc_code')
                            ->where('stock_id',strtoupper($data['itemInfo']->stock_id))
                            ->groupBy('loc_code')
                            ->get();
        
        $data['tab'] = $tab;

        $data['transations'] = DB::table('stock_moves')
                              ->where('stock_moves.stock_id',$data['itemInfo']->stock_id)
                              ->leftjoin('item_code','item_code.stock_id','=','stock_moves.stock_id')
                              ->leftjoin('location','location.loc_code','=','stock_moves.loc_code')
                              ->select('stock_moves.*','item_code.description','location.location_name')
                              ->orderBy('stock_moves.tran_date','DESC')
                              ->get();
        //d($data['transations'],1);                 
        return view('admin.item.item_edit', $data);
    }

    /**
     * Show the form for Copy the specified Item.
     *
     * @param  int  $id
     * @return Item copy page view
     */
    public function copy($id)
    {
        $data['menu'] = 'item';
        $data['header'] = 'item';
        $data['breadcrumb'] = 'copyitem';
        $data['locData'] = DB::table('location')->get();
        $data['taxTypes']     = DB::table('item_tax_types')->get();
        $data['categoryData'] = DB::table('stock_category')->get();
        $data['unitData'] = DB::table('item_unit')->get();
        $data['itemData'] = (new Item)->getItemById($id);
        
        return view('admin.item.item_copy', $data);
    }

    /**
     * Update the specified Item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return redirection Item list page view
     */
    public function updateItemInfo(Request $request)
    {
        $this->validate($request, [
            'stock_id' => 'required',
            'description' => 'required',
            'units' => 'required',
            'item_image' => 'mimes:jpeg,bmp,png'
        ]); 

        $data['description'] = $request->description;
        $data['category_id'] = $request->category_id;
        $data['inactive'] = $request->inactive;
        $data['updated_at'] = date('Y-m-d H:i:s');

        $pic = $request->file('item_image');

        if (isset($pic)) {

          $pic1 = $request->pic;
          if ($pic1 != NULL) {
            $dir = public_path("uploads/itemPic/$pic1");
            if (file_exists($dir)) {
               unlink($dir);  
            }
          }


          $destinationPath = public_path('/uploads/itemPic/');
          $filename = $pic->getClientOriginalName();
          $img = Image::make($request->file('item_image')->getRealPath());

          $img->resize(400,400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$filename);

          $data['item_image'] = $filename;

        }
        
        DB::table('item_code')->where('id', $request->id)->update($data);

            $stockMaster['description'] = $request->description;
            $stockMaster['long_description'] = $request->long_description;
            $stockMaster['units'] = $request->units;
            $stockMaster['category_id'] = $request->category_id;
            $stockMaster['tax_type_id'] = $request->tax_type_id;
            $stockMaster['inactive'] = $request->inactive;
            $stockMaster['updated_at'] = date('Y-m-d H:i:s');

            DB::table('stock_master')->where('stock_id', $request->stock_id)->update($stockMaster);

            \Session::flash('success',trans('message.success.update_success'));
            return redirect()->intended('edit-item/item-info/'.$request->id);

    }

    /**
     * Remove the specified Item from storage.
     *
     * @param  int  $id
     * @return redirect Item list page view
     */
    public function destroy($id)
    {
        if (isset($id)) {
            $record = \DB::table('item_code')->where('id', $id)->first();
            if ($record) {
                DB::table('item_code')->where('id', '=', $id)->update(['deleted_status'=>1]);
                DB::table('stock_master')->where('stock_id', '=', $record->stock_id)->update(['deleted_status'=>1]);
                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('item');
            }
        }
    }

    /**
     * Add item to stock.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Item show page view
     */
    public function addStock(Request $request)
    {
        $p_id = \Auth::user()->id;
        $data['stock_id'] = $request->stock_id;
        $data['type'] = $request->type;
        $data['loc_code'] = $request->loc_code;
        $data['person_id'] = $p_id;
        $data['qty'] = $request->quantity;
        $data['note'] = $request->reference;
        $data['tran_date'] = date('Y-m-d H:i:s');

        $insert_id = DB::table('stock_moves')->insertGetId($data);
        
        $id = $request->id;
        
        if (!empty($insert_id)) {
            \Session::flash('success',trans('message.success.added_success'));
            return redirect()->intended("show-item/$id");
        }
    }

    /**
     * Remove item to stock.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Item show page view
     */
    public function removeStock(Request $request)
    {
        $p_id = \Auth::user()->id; 
        $data['stock_id'] = $request->stock_id;
        $data['type'] = $request->type;
        $data['loc_code'] = $request->loc_code;
        $data['person_id'] = $p_id;
        $data['qty'] = "-".$request->quantity;
        $data['note'] = $request->reference;
        $data['tran_date'] = date('Y-m-d H:i:s');

        $id = $request->id;

        $status = $this->qtyValidate($request->loc_code, $request->stock_id,$request->quantity);
        //dd($status);

        if ($status['fail'] == 1) {
            $nm = $status['stock']; 
            \Session::flash('fail',"You Can not Remove more than $nm item.");
            return redirect()->intended("show-item/$id");
            exit();
        }
        $insert_id = DB::table('stock_moves')->insertGetId($data);
        
        
        if (!empty($insert_id)) {
            \Session::flash('success',trans('message.success.removed_success'));
            return redirect()->intended("show-item/$id");
        }
    }

    /**
     * Move item from one to another stock.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return redirect Item show page view
     */
    public function moveStock(Request $request)
    {
        $id = $request->id;
        $p_id = \Auth::user()->id;
        $data['stock_id'] = $request->stock_id;
        $data['type'] = $request->type;
        $data['loc_code'] = $request->loc_code1;
        $data['person_id'] = $p_id;
        $data['qty'] = "-".$request->quantity;
        $data['note'] = $request->reference;
        $data['tran_date'] = date('Y-m-d H:i:s');

        $status = $this->qtyValidate($request->loc_code1, $request->stock_id,$request->quantity);
        
        if($status['fail'] == 1){

            $nm = $status['stock']; 
            \Session::flash('fail',"You Can not Move more than $nm item.");
            return redirect()->intended("show-item/$id");
            exit();
        }


        $insert_id = DB::table('stock_moves')->insert($data);

        $data2['stock_id'] = $request->stock_id;
        $data2['type'] = $request->type;
        $data2['loc_code'] = $request->loc_code2;
        $data2['person_id'] = $p_id;
        $data2['qty'] = $request->quantity;
        $data2['note'] = $request->reference;
        $data2['tran_date'] = date('Y-m-d H:i:s');

        $insert_id = DB::table('stock_moves')->insert($data2);

        $loc =  DB::table('location')->get();
        $loc_name = array();
        foreach ($loc as $value) {
            $loc_name[$value->loc_code] = $value->location_name;
        }


        $a = $loc_name[$request->loc_code1];
        $b = $request->quantity;
        $c = $loc_name[$request->loc_code2];
        
        \Session::flash('success',trans('message.success.moved_success'));
            return redirect()->intended("show-item/$id");

    }

    /**
     * Validated item quantity if it can be removed from stock.
     */
    public function qtyValidate($loc, $id, $qty)
    {
        $v = [];
        $current_stock = (new Item)->stock_validate($loc, $id);
        
        if ($current_stock->total < $qty) {
            
            $v['stock'] = $current_stock->total;
            $v['fail'] = 1;

            return $v;
            
        } else {
            $v['fail'] = 0;
            return $v;
        }
    }

    /**
     * Validated item quantity if it can be removed from stock. using ajax 
     */
    public function qtyValidAjax()
    {
        $v = [];
        $id = $_POST['mvid'];
        $loc = $_POST['loc1'];
        $qty = $_POST['qty'];

        $current_stock = (new Item)->stock_validate($loc, $id);
        //dd($current_stock);

        if (empty($current_stock)) {
            
            $v['fail'] = 2;
            echo json_encode($v);
            
        } elseif (!empty($current_stock) && $current_stock->total < $qty) {
            
            $v['stock'] = $current_stock->total;
            $v['fail'] = 1;

            echo json_encode($v);
        
        } else {
            $v['fail'] = 0;
            echo json_encode($v);
        }
    }

    /**
     * Validated item Id is unique or not
     */
    public function stockValidChk()
    {
        $st_id = $_POST['stock_id'];
        $v = DB::table('item_code')->where('stock_id',$st_id)->first();
        
        if (!empty($v)) {
             echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * Show full transation details
     */
    public function showFullDetails($id)
    {
        $data['menu'] = 'item';
        $data['header'] = 'item';
        $data['breadcrumb'] = 'historyitem';
        $loc =  DB::table('location')->get();
        $loc_name = array();
        foreach ($loc as $value) {
            $loc_name[$value->loc_code] = $value->location_name;
        }
        
        $data['loc_name'] = $loc_name;
        $data['itemHistory'] = DB::table('stock_moves')
                                ->where(['stock_id'=>$id])
                                ->orderBy('trans_id', 'desc')
                                ->get();


        return view('admin.item.item_transition', $data);
    }

    public function downloadCsv($type)
    {
        if ($type == 'csv' ) {

            $itemdata = (new Item)->getAllItemCsv();
//d($itemdata,1);
            foreach ($itemdata as $key => $value) {
                $csvdata[$key]['Item ID'] = $value->stock_id;
                $csvdata[$key]['Item Name'] = $value->item_name;
                //$csvdata[$key]['Long Description'] = $value['long_description'];
                $csvdata[$key]['Item Category'] = $value->category;
                //$csvdata[$key]['Unit'] = $value['units'];
                //$csvdata[$key]['Tax Type'] = $value['tax_type'];
                $csvdata[$key]['Purchase Price'] = $value->purcashe_price;
                $csvdata[$key]['Retail Price'] = $value->retail_price;
                $csvdata[$key]['Whole Sale Price'] = $value->wholesale_price;
            }
        }
        //d($csvdata,1);

        if( $type == 'sample' ) {
            $csvdata[0]['Item ID'] = 'DELL'; 
            $csvdata[0]['Item Name'] = 'Dell desktop computer';
            $csvdata[0]['Long Description'] = 'This is a destop computer';
            $csvdata[0]['Item Category'] = 'Computer';
            $csvdata[0]['Unit'] = 'pc';
            $csvdata[0]['Tax Type'] = 'Normal';
            $csvdata[0]['Purchase Price'] = '50';
            $csvdata[0]['Retail Price'] = '60';
            $csvdata[0]['Wholesale Price'] = '55';

            $type = 'csv';
        }

        return Excel::create('Item_sheet'.time().'', function($excel) use ($csvdata) {
            
            $excel->sheet('mySheet', function($sheet) use ($csvdata)
            {
                $sheet->fromArray($csvdata);
            });
            
        })->download($type);
    }

    public function import()
    {
        $data['menu'] = 'item';
        
        return view('admin.item.item_import', $data);
    }

    public function importCsv(Request $request)
    {
        $file = $request->file('import_file');
      //  d($file,1);
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
            
            $csv = array_map('str_getcsv', file($path));
        //   d($csv,1);
            $unMatch = [];
            $header = array("Item ID", "Item Name", "Long Description", "Item Category", "Unit", "Tax Type", "Purchase Price", "Retail Price", "Wholesale Price");

            for ($i=0; $i < sizeof($csv[0]); $i++) {
                if (! in_array($csv[0][$i], $header)) {
                    $unMatch[] = $csv[0][$i];
                }
            }

            if(!empty($unMatch)) {

                return back()->withErrors(['email' => "Please Check Csv Header Name !"]);
            }
            
            $data = [];
            foreach ($csv as $key => $value) {
                if($key != 0) {
                    $data[$key]['item_id'] = $value[0];
                    $data[$key]['item_name'] = $value[1];
                    $data[$key]['long_description'] = $value[2];
                    $data[$key]['item_category'] = $value[3];
                    $data[$key]['unit'] = $value[4];
                    $data[$key]['tax_type'] = $value[5];
                    $data[$key]['purch_price'] = $value[6];
                    $data[$key]['retail_price'] = $value[7];
                    $data[$key]['whole_price'] = $value[8];
                }
            }

            $data = array_values($data);


            $cat_id = array();
            $cat =  DB::table('stock_category')->get();

            foreach ($cat as $value) {
                $cat_id[$value->description] = $value->category_id;
            }

            $tax_id = array();
            $tax = DB::table('item_tax_types')->get();

            foreach ($tax as $value) {
                $tax_id[$value->name] = $value->id;
            }
            

            
            if (!empty($data)) {

                $item = uniqueMultidimArray($data,'item_id');
                sort($item);
                foreach ($item as $key => $value) {
                    
                    $itemCount = DB::table('item_code')->where('stock_id','=',$value['item_id'])->count();
                    $categoryCount = DB::table('stock_category')->where('description','=',$value['item_category'])->count();
                    $unitCount = DB::table('item_unit')->where('name','=',$value['unit'])->count();
                    /*d($value);
                    d($itemCount);
                    d($categoryCount);
                    d($unitCount,1);*/

                    if ($itemCount == 0 && $categoryCount > 0 && $unitCount > 0) {
                        //d($data);
                        $itemCode[] = [
                                'stock_id' => $value['item_id'],
                                'description' => $value['item_name'],
                                'category_id' => $cat_id[$value['item_category']]
                            ];
                        
                        $stockMaster[] = [
                                'stock_id' => strtoupper($value['item_id']),
                                'description' => $value['item_name'],
                                'long_description' => $value['long_description'],
                                'units' => $value['unit'],
                                'tax_type_id' => $tax_id[$value['tax_type']],
                                'category_id' => $cat_id[$value['item_category']]
                            ];

                        $purchPrice[] = [
                                'stock_id' => strtoupper($value['item_id']),
                                'price' => $value['purch_price'],
                               // 'supplier_id' => 0
                            ];

                        $salePrice[] = [
                                'stock_id' => strtoupper($value['item_id']),
                                'r_price' => $value['retail_price'],
                                'w_price' => $value['whole_price']
                            ];
                    }else{
                        return back()->withErrors(['email' => "Please Check Csv File !"]);
                    }
                }
                //d($itemCode,1);
                /*d($itemCode);
                d($salePrice);
                d($purchPrice,1);*/

                $k = 0;
                foreach ($salePrice as $key => $value) {
                    $retailPrice[$key]['stock_id'] = strtoupper($value['stock_id']);
                    $retailPrice[$key]['sales_type_id'] = 1;
                    $retailPrice[$key]['price'] = $value['r_price'] ? $value['r_price'] : 0;
                    $retailPrice[$key]['curr_abrev'] = 'USD';

                    $wholePrice[$key]['stock_id'] = strtoupper($value['stock_id']);
                    $wholePrice[$key]['sales_type_id'] = 2;
                    $wholePrice[$key]['price'] = $value['w_price'] ? $value['w_price'] : 0;
                    $wholePrice[$key]['curr_abrev'] = 'USD';
                }

                
                if (!empty($itemCode)) {
                    DB::table('item_code')->insert($itemCode);
                    DB::table('stock_master')->insert($stockMaster);
                    DB::table('purchase_prices')->insert($purchPrice);
                    DB::table('sale_prices')->insert($retailPrice);
                    DB::table('sale_prices')->insert($wholePrice);
                    
                    \Session::flash('success',trans('message.success.import_success'));
                    return redirect()->intended('item');
                }else{
                        return back()->withErrors(['email' => "Please Check Csv File !"]);
                    }
            }
        }
        return back();
    }
}
