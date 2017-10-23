<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use PDF;
use Validator;
use Auth;
use Session;
use \Milon\Barcode\DNS1D;

class BarcodeController extends Controller
{
    public function __construct(Request $request){
        $this->request = $request;
    }

    public function index()
    {
        $data['items'] = [];
        $data['quantities'] = [];
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'barcode';
        $data['header'] = 'barcode';

        
        if(isset($_POST)){
        $stock_id = $this->request->stock_id;
        $items = $this->request->name;
        $quantity = $this->request->quantity;
        $perpage = $this->request->perpage;
        $product_name = $this->request->product_name;
        $company_name = $this->request->site_name;

            if(!empty($items) && !empty($quantity)){
                $data['items'] = $items;
                $data['stock_ids'] = $stock_id;
                $data['quantities'] = $quantity;
                $data['perpage'] = $perpage;
                $data['product_name'] = $product_name;
                $data['company_name'] = $company_name;
                //d($data,1);
            }
        }

        return view('admin.barcode.create', $data);
    }

  public function search()
    {
           
            $data = array();
            $data['status_no'] = 0;
            $data['message']   ='No Item Found!';
            $data['items'] = array();

            $item = DB::table('item_code')
            ->where('description','LIKE','%'.$this->request->search.'%')
            ->orWhere('stock_id','LIKE','%'.$this->request->search.'%')
            ->get();

            if(!empty($item)){
                
                $data['status_no'] = 1;
                $data['message']   ='Item Found';

                $i = 0;

                foreach ($item as $key => $value) {
                    $items[$i]['id'] = $value->id;
                    $items[$i]['stock_id'] = $value->stock_id;
                    $items[$i]['description'] = $value->description;
                    $i++;
                }
                 $data['items'] = $items;

            }

            echo json_encode($data);
            exit;            
    }

}
