<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
	protected $table = 'purch_orders';

    public function getAllPurchOrder()
    { 
        return $this->leftJoin('suppliers', 'purch_orders.supplier_id', '=', 'suppliers.supplier_id')
                    ->leftJoin('location', 'purch_orders.into_stock_location', '=', 'location.loc_code')
                    ->select('purch_orders.*', 'suppliers.supp_name','location.location_name as loc_name')
                    ->orderBy('purch_orders.ord_date', 'desc')
                    ->get();
    }

    public function getAllPurchOrderById($sid)
    { 
        return $this->leftJoin('suppliers', 'purch_orders.supplier_id', '=', 'suppliers.supplier_id')
                    ->leftJoin('location', 'purch_orders.into_stock_location', '=', 'location.loc_code')
                    ->select('purch_orders.*', 'suppliers.supp_name','location.location_name as loc_name')
                    ->orderBy('purch_orders.order_no', 'desc')
                    ->where('purch_orders.supplier_id',$sid)
                    ->get();
    }

    public function getPurchaseInvoiceByID($id)
    {
          $data = DB::table('purch_order_details')
                ->where(['order_no'=>$id])
                ->leftJoin('item_code', 'purch_order_details.item_code', '=', 'item_code.stock_id')
                ->leftjoin('item_tax_types','item_tax_types.id','=','purch_order_details.tax_type_id')
                ->leftJoin('purchase_prices', 'purch_order_details.item_code', '=', 'purchase_prices.stock_id')
                ->select('purch_order_details.*', 'item_code.id as item_id','item_tax_types.tax_rate','item_tax_types.id as tax_id','purchase_prices.id')
                ->get();
        return $data;
    }

    public function getSalseInvoicePdf($id)
    { 
        return $this->where(['sales_orders.order_no'=>$id])
                    ->leftJoin('debtors_master', 'sales_orders.debtor_no', '=', 'debtors_master.debtor_no')
                    ->leftJoin('purch_order_details', 'sales_orders.order_no', '=', 'purch_order_details.order_no')
                    ->select('sales_orders.*', 'debtors_master.*')
                    ->first();
    }

    public function calculateTaxRow($order_no){
        $tax_rows = DB::table('purch_order_details')
                ->join('item_tax_types', 'item_tax_types.id', '=', 'purch_order_details.tax_type_id')
                ->select(DB::raw('(purch_order_details.qty_invoiced*purch_order_details.unit_price*item_tax_types.tax_rate)/100 AS tax_amount,item_tax_types.tax_rate'))
                ->where('order_no', $order_no)
                ->get();

        $tax_amount = [];
        $tax_rate   =[];
        $i=0;
        foreach ($tax_rows as $key => $value) {
           if(isset($tax_amount[$value->tax_rate])){
                $tax_amount[strval($value->tax_rate)] +=$value->tax_amount;
           }else{
             $tax_amount[strval($value->tax_rate)] =$value->tax_amount;
           }
        }
        return $tax_amount;
    }


    public function getAllPurchOrderFiltering($from,$to,$supplier,$item){
        $from = DbDateFormat($from);
        $to = DbDateFormat($to);

        if ( $supplier == 'all' && $item == 'all' ){
        $data = $this->leftJoin('suppliers', 'purch_orders.supplier_id', '=', 'suppliers.supplier_id')
                    ->leftJoin('location', 'purch_orders.into_stock_location', '=', 'location.loc_code')
                    ->select('purch_orders.*', 'suppliers.supp_name','location.location_name as loc_name')
                    ->whereDate('purch_orders.ord_date','>=',$from)
                    ->whereDate('purch_orders.ord_date','<=',$to)

                    ->orderBy('purch_orders.ord_date', 'desc')
                    ->get();

        }elseif ($supplier != 'all' && $item == 'all') {

        $data = $this->leftJoin('suppliers', 'purch_orders.supplier_id', '=', 'suppliers.supplier_id')
                    ->leftJoin('location', 'purch_orders.into_stock_location', '=', 'location.loc_code')
                    ->select('purch_orders.*', 'suppliers.supp_name','location.location_name as loc_name')
                    ->whereDate('purch_orders.ord_date','>=',$from)
                    ->whereDate('purch_orders.ord_date','<=',$to)
                    ->where('purch_orders.supplier_id',$supplier)
                    ->orderBy('purch_orders.ord_date', 'desc')
                    ->get();

        }
        elseif ($supplier == 'all' && $item != 'all') {

        $data = $this->leftJoin('suppliers', 'purch_orders.supplier_id', '=', 'suppliers.supplier_id')
                    ->leftJoin('location', 'purch_orders.into_stock_location', '=', 'location.loc_code')
                    ->select('purch_orders.*', 'suppliers.supp_name','location.location_name as loc_name')
                    ->leftjoin('purch_order_details','purch_order_details.order_no','=','purch_orders.order_no')
                    ->where('purch_order_details.item_code',$item)
                    ->whereDate('purch_orders.ord_date','>=',$from)
                    ->whereDate('purch_orders.ord_date','<=',$to)                    
                    ->orderBy('purch_orders.ord_date', 'desc')
                    ->get();

        }
        elseif ($supplier != 'all' && $item != 'all') {

        $data = $this->leftJoin('suppliers', 'purch_orders.supplier_id', '=', 'suppliers.supplier_id')
                    ->leftJoin('location', 'purch_orders.into_stock_location', '=', 'location.loc_code')
                    ->select('purch_orders.*', 'suppliers.supp_name','location.location_name as loc_name')
                    ->leftjoin('purch_order_details','purch_order_details.order_no','=','purch_orders.order_no')
                    ->where('purch_order_details.item_code',$item)
                    ->where('purch_orders.supplier_id',$supplier)
                    ->whereDate('purch_orders.ord_date','>=',$from)
                    ->whereDate('purch_orders.ord_date','<=',$to)
                    ->orderBy('purch_orders.ord_date', 'desc')
                    ->get();

        }

        return $data;
    }

    public function getAllPurchOrderByUserId($from, $to, $supplier, $location, $sid)
    { 
        $from = DbDateFormat($from);
        $to = DbDateFormat($to);

        if($supplier == 'all' && $location == 'all'){
        return $this->leftJoin('suppliers', 'purch_orders.supplier_id', '=', 'suppliers.supplier_id')
                    ->leftJoin('location', 'purch_orders.into_stock_location', '=', 'location.loc_code')
                    ->select('purch_orders.*', 'suppliers.supp_name','location.location_name as loc_name')
                    ->orderBy('purch_orders.ord_date', 'desc')
                    ->whereDate('purch_orders.ord_date','>=', $from)
                    ->whereDate('purch_orders.ord_date','<=', $to)
                    ->where('purch_orders.person_id',$sid)
                    ->get();

        }else if($supplier == 'all' && $location != 'all'){
        return $this->leftJoin('suppliers', 'purch_orders.supplier_id', '=', 'suppliers.supplier_id')
                    ->leftJoin('location', 'purch_orders.into_stock_location', '=', 'location.loc_code')
                    ->select('purch_orders.*', 'suppliers.supp_name','location.location_name as loc_name')
                    ->orderBy('purch_orders.ord_date', 'desc')
                    ->whereDate('purch_orders.ord_date','>=', $from)
                    ->whereDate('purch_orders.ord_date','<=', $to)
                    ->where(['purch_orders.person_id'=>$sid,'purch_orders.into_stock_location'=>$location])
                    ->get();

        }else if($supplier != 'all' && $location == 'all'){
        return $this->leftJoin('suppliers', 'purch_orders.supplier_id', '=', 'suppliers.supplier_id')
                    ->leftJoin('location', 'purch_orders.into_stock_location', '=', 'location.loc_code')
                    ->select('purch_orders.*', 'suppliers.supp_name','location.location_name as loc_name')
                    ->orderBy('purch_orders.ord_date', 'desc')
                    ->whereDate('purch_orders.ord_date','>=', $from)
                    ->whereDate('purch_orders.ord_date','<=', $to)
                    ->where(['purch_orders.person_id'=>$sid,'purch_orders.supplier_id'=>$supplier])
                    ->get();

        }else if($supplier != 'all' && $location != 'all'){
        return $this->leftJoin('suppliers', 'purch_orders.supplier_id', '=', 'suppliers.supplier_id')
                    ->leftJoin('location', 'purch_orders.into_stock_location', '=', 'location.loc_code')
                    ->select('purch_orders.*', 'suppliers.supp_name','location.location_name as loc_name')
                    ->orderBy('purch_orders.ord_date', 'desc')
                    ->whereDate('purch_orders.ord_date','>=', $from)
                    ->whereDate('purch_orders.ord_date','<=', $to)
                    ->where(['purch_orders.person_id'=>$sid,'purch_orders.supplier_id'=>$supplier,'purch_orders.into_stock_location'=>$location])
                    ->get();

        }
    }

    public function getPurchaseReport($type,$from,$to,$year,$month,$item,$supplier,$location){

        if($type=='daily'){
            if($item == 'all' && $supplier == 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                                             
                            WHERE MONTH(po.ord_date) = MONTH(NOW())
                            GROUP BY(po.ord_date)
                            ORDER BY po.ord_date ASC");
            }
            else if($item == 'all' && $supplier == 'all' && $location != 'all'){
              
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                                             
                            WHERE MONTH(po.ord_date) = MONTH(NOW()) AND po.into_stock_location = '$location'
                            GROUP BY(po.ord_date)
                            ORDER BY po.ord_date ASC");              
            }
            else if($item == 'all' && $supplier != 'all' && $location == 'all'){
                
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                                             
                            WHERE MONTH(po.ord_date) = MONTH(NOW()) AND po.supplier_id = '$supplier'
                            GROUP BY(po.ord_date)
                            ORDER BY po.ord_date ASC");             
            }
            else if($item == 'all' && $supplier != 'all' && $location != 'all'){
              
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                                             
                            WHERE MONTH(po.ord_date) = MONTH(NOW()) AND po.into_stock_location = '$location' AND po.supplier_id = '$supplier'  
                            GROUP BY(po.ord_date)
                            ORDER BY po.ord_date ASC");              
            }
            else if($item != 'all' && $supplier == 'all' && $location == 'all'){
              
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                                             
                            WHERE MONTH(po.ord_date) = MONTH(NOW()) AND pod.item_code = '$item' 
                            GROUP BY(po.ord_date)
                            ORDER BY po.ord_date ASC");              
            }
            else if($item != 'all' && $supplier == 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                                             
                            WHERE MONTH(po.ord_date) = MONTH(NOW()) AND pod.item_code = '$item' AND po.into_stock_location = '$location'
                            GROUP BY(po.ord_date)
                            ORDER BY po.ord_date ASC");               
            }
            else if($item != 'all' && $supplier != 'all' && $location == 'all'){
              
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                                             
                            WHERE MONTH(po.ord_date) = MONTH(NOW()) AND pod.item_code = '$item' AND po.supplier_id = '$supplier'  
                            GROUP BY(po.ord_date)
                            ORDER BY po.ord_date ASC");              
            }
            else if($item != 'all' && $supplier != 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                                             
                            WHERE MONTH(po.ord_date) = MONTH(NOW()) AND pod.item_code = '$item' AND po.supplier_id = '$supplier' AND po.into_stock_location = '$location'
                            GROUP BY(po.ord_date)
                            ORDER BY po.ord_date ASC");               
            }

       }else if($type=='monthly'){
        
      if($item == 'all' && $supplier == 'all' && $location == 'all'){
            $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                                         
                        WHERE YEAR(po.ord_date) = YEAR(NOW())
                        GROUP BY MONTH(po.ord_date)
                        ORDER BY po.ord_date DESC");
        }else if($item == 'all' && $supplier == 'all' && $location != 'all'){
            $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                                         
                        WHERE YEAR(po.ord_date) = YEAR(NOW())
                        AND po.into_stock_location = '$location'
                        GROUP BY MONTH(po.ord_date)
                        ORDER BY po.ord_date DESC");
        }else if($item == 'all' && $supplier != 'all' && $location == 'all'){
            $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                                         
                        WHERE YEAR(po.ord_date) = YEAR(NOW())
                        AND po.supplier_id = '$supplier'
                        GROUP BY MONTH(po.ord_date)
                        ORDER BY po.ord_date DESC");
        }else if($item == 'all' && $supplier != 'all' && $location != 'all'){
            $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                                         
                        WHERE YEAR(po.ord_date) = YEAR(NOW())
                        AND po.supplier_id = '$supplier' AND po.into_stock_location = '$location'
                        GROUP BY MONTH(po.ord_date)
                        ORDER BY po.ord_date DESC");
        }else if($item != 'all' && $supplier == 'all' && $location == 'all'){
            $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                                         
                        WHERE YEAR(po.ord_date) = YEAR(NOW())
                        
                        AND pod.item_code = '$item'

                        GROUP BY MONTH(po.ord_date)
                        ORDER BY po.ord_date DESC");
        }else if($item != 'all' && $supplier == 'all' && $location != 'all'){
            $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                                         
                        WHERE YEAR(po.ord_date) = YEAR(NOW())
                        AND pod.item_code = '$item' AND po.into_stock_location = '$location' 
                        GROUP BY MONTH(po.ord_date)
                        ORDER BY po.ord_date DESC");
        }else if($item != 'all' && $supplier != 'all' && $location == 'all'){
            $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty
                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no           
                        WHERE YEAR(po.ord_date) = YEAR(NOW())
                        AND pod.item_code = '$item' AND po.supplier_id = '$supplier'
                        GROUP BY MONTH(po.ord_date)
                        ORDER BY po.ord_date DESC");
        }else if($item != 'all' && $supplier != 'all' && $location != 'all'){
            $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty
                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no            
                        WHERE YEAR(po.ord_date) = YEAR(NOW())
                        AND pod.item_code = '$item' AND po.supplier_id = '$supplier' AND po.into_stock_location = '$location'
                        GROUP BY MONTH(po.ord_date)
                        ORDER BY po.ord_date DESC");            
        }

       }else if($type=='yearly'){

        if($year=='all'){

            if($item == 'all' && $supplier == 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                                         
                        GROUP BY YEAR(po.ord_date)
                        ORDER BY po.ord_date DESC");
            }else if($item == 'all' && $supplier == 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                        WHERE po.into_stock_location = '$location'

                        GROUP BY YEAR(po.ord_date)
                        ORDER BY po.ord_date DESC");                
            }
            else if($item == 'all' && $supplier != 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                        WHERE po.supplier_id = '$supplier'
                                     
                        GROUP BY YEAR(po.ord_date)
                        ORDER BY po.ord_date DESC");
            }
            else if($item == 'all' && $supplier != 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                        WHERE po.supplier_id = '$supplier' AND po.into_stock_location = '$location'
                                     
                        GROUP BY YEAR(po.ord_date)
                        ORDER BY po.ord_date DESC");
            }
            else if($item != 'all' && $supplier == 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                        WHERE po.item_code = '$item'
                                     
                        GROUP BY YEAR(po.ord_date)
                        ORDER BY po.ord_date DESC");
            }
            else if($item != 'all' && $supplier == 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                        WHERE pod.item_code = '$item' AND po.into_stock_location = '$location'
                                     
                        GROUP BY YEAR(po.ord_date)
                        ORDER BY po.ord_date DESC");
            }
            else if($item != 'all' && $supplier != 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                        WHERE pod.item_code = '$item' AND po.supplier_id = '$supplier'
                                     
                        GROUP BY YEAR(po.ord_date)
                        ORDER BY po.ord_date DESC");                
            }
            else if($item != 'all' && $supplier != 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                        po.ord_date,
                        count(po.order_no) as order_total,
                        SUM(po.total) as cost,
                        SUM(pod.qty) as qty

                        FROM purch_orders as po 
                        LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code FROM purch_order_details GROUP BY order_no) as pod
                        ON pod.order_no = po.order_no 
                        WHERE pod.item_code = '$item' AND po.supplier_id = '$supplier' AND po.into_stock_location = '$location'
                                     
                        GROUP BY YEAR(po.ord_date)
                        ORDER BY po.ord_date DESC");  

            }

        }elseif($year != 'all'){
            if($month=='all'){
                if($item == 'all' && $supplier == 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                            WHERE YEAR(po.ord_date) = $year
                            GROUP BY MONTH(po.ord_date)
                            ORDER BY po.ord_date DESC");
                }else if($item == 'all' && $supplier == 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                            WHERE YEAR(po.ord_date) = $year AND po.into_stock_location = '$location' 
                            GROUP BY MONTH(po.ord_date)
                            ORDER BY po.ord_date DESC");
                }
                else if($item == 'all' && $supplier != 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                            WHERE YEAR(po.ord_date) = '$year' AND po.supplier_id = '$supplier' 
                            GROUP BY MONTH(po.ord_date)
                            ORDER BY po.ord_date DESC");
                }
                else if($item == 'all' && $supplier != 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                            WHERE YEAR(po.ord_date) = '$year' AND po.supplier_id = '$supplier' AND po.into_stock_location = '$location' 
                            GROUP BY MONTH(po.ord_date)
                            ORDER BY po.ord_date DESC");   
                }
                else if($item != 'all' && $supplier == 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                            WHERE YEAR(po.ord_date) = '$year' AND pod.item_code = '$item' 
                            GROUP BY MONTH(po.ord_date)
                            ORDER BY po.ord_date DESC"); 
                }
                else if($item != 'all' && $supplier == 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                            WHERE YEAR(po.ord_date) = '$year' AND po.into_stock_location = '$location' AND pod.item_code = '$item' 
                            GROUP BY MONTH(po.ord_date)
                            ORDER BY po.ord_date DESC");
                }
                else if($item != 'all' && $supplier != 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                            WHERE YEAR(po.ord_date) = '$year' AND po.supplier_id = '$supplier' AND pod.item_code = '$item' 
                            GROUP BY MONTH(po.ord_date)
                            ORDER BY po.ord_date DESC");                    
                }
                else if($item != 'all' && $supplier != 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty

                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no 
                            WHERE YEAR(po.ord_date) = '$year' AND po.supplier_id = '$supplier' AND pod.item_code = '$item' AND po.into_stock_location = '$location'
                            GROUP BY MONTH(po.ord_date)
                            ORDER BY po.ord_date DESC");                     
                }

            }else{
                if($item == 'all' && $supplier == 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty
                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no          
                            WHERE 
                                MONTH(po.ord_date) = '$month'
                            AND 
                                YEAR(po.ord_date) = '$year'
                            GROUP BY po.ord_date
                            ORDER BY po.ord_date ASC");
                }else if($item == 'all' && $supplier == 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty
                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no          
                            WHERE 
                                MONTH(po.ord_date) = '$month'
                            AND 
                                YEAR(po.ord_date) = '$year'
                            AND po.into_stock_location = '$location'
                            GROUP BY po.ord_date
                            ORDER BY po.ord_date ASC");
                }
                else if($item == 'all' && $supplier != 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty
                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no          
                            WHERE 
                                MONTH(po.ord_date) = '$month'
                            AND 
                                YEAR(po.ord_date) = '$year'
                            AND po.supplier_id = '$supplier'
                            GROUP BY po.ord_date
                            ORDER BY po.ord_date ASC");
                }
                else if($item == 'all' && $supplier != 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty
                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no          
                            WHERE 
                                MONTH(po.ord_date) = '$month'
                            AND 
                                YEAR(po.ord_date) = '$year'
                            AND 
                                po.supplier_id = '$supplier'
                            AND 
                                po.into_stock_location = '$location'
                            GROUP BY po.ord_date
                            ORDER BY po.ord_date ASC");                    
                }
                else if($item != 'all' && $supplier == 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty
                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no          
                            WHERE 
                                MONTH(po.ord_date) = '$month'
                            AND 
                                YEAR(po.ord_date) = '$year'
                            AND 
                                pod.item_code = '$item'
                            GROUP BY po.ord_date
                            ORDER BY po.ord_date ASC");                     
                }
                else if($item != 'all' && $supplier == 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty
                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no          
                            WHERE 
                                MONTH(po.ord_date) = '$month'
                            AND 
                                YEAR(po.ord_date) = '$year'
                            AND 
                                pod.item_code = '$item'
                            AND 
                                po.into_stock_location = '$location'
                            GROUP BY 
                                po.ord_date
                            ORDER BY po.ord_date ASC");                    
                }
                else if($item != 'all' && $supplier != 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty
                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no          
                            WHERE 
                                MONTH(po.ord_date) = '$month'
                            AND 
                                YEAR(po.ord_date) = '$year'
                            AND 
                                pod.item_code = '$item'
                            AND 
                                po.supplier_id = '$supplier'
                            GROUP BY 
                                po.ord_date
                            ORDER BY po.ord_date ASC");                    
                }
                else if($item != 'all' && $supplier != 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                            po.ord_date,
                            count(po.order_no) as order_total,
                            SUM(po.total) as cost,
                            SUM(pod.qty) as qty
                            FROM purch_orders as po 
                            LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code  FROM purch_order_details GROUP BY order_no) as pod
                            ON pod.order_no = po.order_no          
                            WHERE 
                                MONTH(po.ord_date) = '$month'
                            AND 
                                YEAR(po.ord_date) = '$year'
                            AND 
                                pod.item_code = '$item'
                            AND 
                                po.supplier_id = '$supplier'
                            AND po.into_stock_location = '$location'
                            GROUP BY 
                                po.ord_date
                            ORDER BY po.ord_date ASC");
                }
            }
        }
       }elseif($type=='custom'){
            if($item == 'all' && $supplier == 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                    po.ord_date,
                    count(po.order_no) as order_total,
                    SUM(po.total) as cost,
                    SUM(pod.qty) as qty

                    FROM purch_orders as po 
                    LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty  FROM purch_order_details GROUP BY order_no) as pod
                    ON pod.order_no = po.order_no 
                                     
                    WHERE po.ord_date BETWEEN '$from' AND '$to'
                    GROUP BY(po.ord_date)
                    ORDER BY po.ord_date ASC");                
            }else if($item == 'all' && $supplier == 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                    po.ord_date,
                    count(po.order_no) as order_total,
                    SUM(po.total) as cost,
                    SUM(pod.qty) as qty

                    FROM purch_orders as po 
                    LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty  FROM purch_order_details GROUP BY order_no) as pod
                    ON pod.order_no = po.order_no 
                                     
                    WHERE po.ord_date BETWEEN '$from' AND '$to'
                    AND po.into_stock_location = '$location'
                    GROUP BY(po.ord_date)
                    ORDER BY po.ord_date ASC");                 
            }
            else if($item == 'all' && $supplier != 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                    po.ord_date,
                    count(po.order_no) as order_total,
                    SUM(po.total) as cost,
                    SUM(pod.qty) as qty

                    FROM purch_orders as po 
                    LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty  FROM purch_order_details GROUP BY order_no) as pod
                    ON pod.order_no = po.order_no
                    WHERE po.ord_date BETWEEN '$from' AND '$to'
                    AND po.supplier_id = '$supplier'
                    GROUP BY(po.ord_date)
                    ORDER BY po.ord_date ASC");
            }
            else if($item == 'all' && $supplier != 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                    po.ord_date,
                    count(po.order_no) as order_total,
                    SUM(po.total) as cost,
                    SUM(pod.qty) as qty

                    FROM purch_orders as po 
                    LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty  FROM purch_order_details GROUP BY order_no) as pod
                    ON pod.order_no = po.order_no
                    WHERE po.ord_date BETWEEN '$from' AND '$to'
                    AND po.supplier_id = '$supplier' AND po.into_stock_location = '$location'
                    GROUP BY(po.ord_date)
                    ORDER BY po.ord_date ASC");
            }
            else if($item != 'all' && $supplier == 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                    po.ord_date,
                    count(po.order_no) as order_total,
                    SUM(po.total) as cost,
                    SUM(pod.qty) as qty

                    FROM purch_orders as po 
                    LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code  FROM purch_order_details GROUP BY order_no) as pod
                    ON pod.order_no = po.order_no
                    WHERE po.ord_date BETWEEN '$from' AND '$to'
                    AND pod.item_code = '$item'
                    GROUP BY(po.ord_date)
                    ORDER BY po.ord_date ASC");                
            }
            else if($item != 'all' && $supplier == 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                    po.ord_date,
                    count(po.order_no) as order_total,
                    SUM(po.total) as cost,
                    SUM(pod.qty) as qty

                    FROM purch_orders as po 
                    LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code  FROM purch_order_details GROUP BY order_no) as pod
                    ON pod.order_no = po.order_no
                    WHERE po.ord_date BETWEEN '$from' AND '$to'
                    AND pod.item_code = '$item' AND po.into_stock_location = '$location'
                    GROUP BY(po.ord_date)
                    ORDER BY po.ord_date ASC"); 
            }
            else if($item != 'all' && $supplier != 'all' && $location == 'all'){
                $data = DB::select("SELECT 
                    po.ord_date,
                    count(po.order_no) as order_total,
                    SUM(po.total) as cost,
                    SUM(pod.qty) as qty

                    FROM purch_orders as po 
                    LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code  FROM purch_order_details GROUP BY order_no) as pod
                    ON pod.order_no = po.order_no
                    WHERE po.ord_date BETWEEN '$from' AND '$to'
                    AND pod.item_code = '$item' AND po.supplier_id = '$supplier'
                    GROUP BY(po.ord_date)
                    ORDER BY po.ord_date ASC");                
            }
            else if($item != 'all' && $supplier != 'all' && $location != 'all'){
                $data = DB::select("SELECT 
                    po.ord_date,
                    count(po.order_no) as order_total,
                    SUM(po.total) as cost,
                    SUM(pod.qty) as qty

                    FROM purch_orders as po 
                    LEFT JOIN(SELECT order_no, SUM(qty_invoiced)as qty,item_code  FROM purch_order_details GROUP BY order_no) as pod
                    ON pod.order_no = po.order_no
                    WHERE po.ord_date BETWEEN '$from' AND '$to'
                    AND pod.item_code = '$item' AND po.supplier_id = '$supplier' AND po.into_stock_location = '$location'
                    GROUP BY(po.ord_date)
                    ORDER BY po.ord_date ASC");                 
            }

       }
        return $data;
    }

    public function getPurchaseReportDateWise($date){
       $data = $this->leftJoin('suppliers', 'purch_orders.supplier_id', '=', 'suppliers.supplier_id')
                    ->select('purch_orders.*', 'suppliers.supp_name')
                    ->orderBy('purch_orders.ord_date', 'desc')
                    ->where('purch_orders.ord_date',$date)
                    ->get();
        return $data;
    }

    public function getPurchaseYears(){
        $data = DB::select("SELECT DISTINCT YEAR(ord_date) as year FROM purch_orders ORDER BY ord_date DESC");
        return $data;
    }

}
