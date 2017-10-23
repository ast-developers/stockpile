<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Report extends Model
{
	
    public function getInventoryStockOnHand($type,$location)
    {
    	if($type=='all' && $location=='all'){
    	$data = DB::select(DB::raw("SELECT item.stock_id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.qty,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master WHERE inactive=0 AND deleted_status = 0)item

			LEFT JOIN(SELECT stock_id,price FROM sale_prices WHERE sales_type_id = 1)sp
			ON sp.stock_id = item.stock_id

			LEFT JOIN(SELECT stock_id,sum(qty)as qty FROM stock_moves GROUP BY stock_id)sm
			ON sm.stock_id = item.stock_id

			LEFT JOIN(SELECT `item_code` as stock_id,SUM(`unit_price`*`qty_invoiced`) as price,SUM(`qty_invoiced`) as received_qty FROM `purch_order_details` GROUP BY `item_code` )pod
			ON pod.stock_id = item.stock_id
    		"));
    	}else if($type=='all' && $location !='all'){
    	
    	$data = DB::select(DB::raw("SELECT item.stock_id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.qty,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master WHERE inactive=0 AND deleted_status = 0)item

			LEFT JOIN(SELECT stock_id,price FROM sale_prices WHERE sales_type_id = 1)sp
			ON sp.stock_id = item.stock_id

			LEFT JOIN(SELECT stock_id,sum(qty)as qty FROM stock_moves WHERE loc_code = '$location' GROUP BY stock_id)sm
			ON sm.stock_id = item.stock_id

			LEFT JOIN(SELECT `item_code` as stock_id,SUM(`unit_price`*`qty_invoiced`) as price,SUM(`qty_invoiced`) as received_qty FROM `purch_order_details` GROUP BY `item_code` )pod
			ON pod.stock_id = item.stock_id
    		"));
    	}else if($type !='all' && $location =='all'){
    	
    	$data = DB::select(DB::raw("SELECT item.stock_id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.qty,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master WHERE category_id='$type' AND inactive=0 AND deleted_status = 0)item

			LEFT JOIN(SELECT stock_id,price FROM sale_prices WHERE sales_type_id = 1)sp
			ON sp.stock_id = item.stock_id

			LEFT JOIN(SELECT stock_id,sum(qty)as qty FROM stock_moves GROUP BY stock_id)sm
			ON sm.stock_id = item.stock_id

			LEFT JOIN(SELECT `item_code` as stock_id,SUM(`unit_price`*`qty_invoiced`) as price,SUM(`qty_invoiced`) as received_qty FROM `purch_order_details` GROUP BY `item_code` )pod
			ON pod.stock_id = item.stock_id
    		"));

    	}

		else if($type !='all' && $location !='all'){
    	
    	$data = DB::select(DB::raw("SELECT item.stock_id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.qty,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master WHERE category_id='$type' AND inactive=0 AND deleted_status = 0)item

			LEFT JOIN(SELECT stock_id,price FROM sale_prices WHERE sales_type_id = 1)sp
			ON sp.stock_id = item.stock_id

			LEFT JOIN(SELECT stock_id,sum(qty)as qty FROM stock_moves WHERE loc_code = '$location' GROUP BY stock_id)sm
			ON sm.stock_id = item.stock_id

			LEFT JOIN(SELECT `item_code` as stock_id,SUM(`unit_price`*`qty_invoiced`) as price,SUM(`qty_invoiced`) as received_qty FROM `purch_order_details` GROUP BY `item_code` )pod
			ON pod.stock_id = item.stock_id
    		"));

    	}

    	return $data;
    }
    public function getInventoryStockOnHandPdf($type,$location)
    {

    	if($type=='all' && $location=='all'){
    	$data = DB::select(DB::raw("SELECT item.stock_id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.qty,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master)item

			LEFT JOIN(SELECT stock_id,price FROM sale_prices WHERE sales_type_id = 1)sp
			ON sp.stock_id = item.stock_id

			LEFT JOIN(SELECT stock_id,sum(qty)as qty FROM stock_moves GROUP BY stock_id)sm
			ON sm.stock_id = item.stock_id

			LEFT JOIN(SELECT `item_code` as stock_id,SUM(`unit_price`*`qty_invoiced`) as price,SUM(`qty_invoiced`) as received_qty FROM `purch_order_details` GROUP BY `item_code` )pod
			ON pod.stock_id = item.stock_id
    		"));
    	}else if($type=='all' && $location !='all'){
    	
    	$data = DB::select(DB::raw("SELECT item.stock_id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.qty,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master)item

			LEFT JOIN(SELECT stock_id,price FROM sale_prices WHERE sales_type_id = 1)sp
			ON sp.stock_id = item.stock_id

			LEFT JOIN(SELECT stock_id,sum(qty)as qty FROM stock_moves WHERE loc_code = '$location' GROUP BY stock_id)sm
			ON sm.stock_id = item.stock_id

			LEFT JOIN(SELECT `item_code` as stock_id,SUM(`unit_price`*`qty_invoiced`) as price,SUM(`qty_invoiced`) as received_qty FROM `purch_order_details` GROUP BY `item_code` )pod
			ON pod.stock_id = item.stock_id
    		"));
    	}else if($type !='all' && $location =='all'){
    	
    	$data = DB::select(DB::raw("SELECT item.stock_id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.qty,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master WHERE category_id='$type')item

			LEFT JOIN(SELECT stock_id,price FROM sale_prices WHERE sales_type_id = 1)sp
			ON sp.stock_id = item.stock_id

			LEFT JOIN(SELECT stock_id,sum(qty)as qty FROM stock_moves GROUP BY stock_id)sm
			ON sm.stock_id = item.stock_id

			LEFT JOIN(SELECT `item_code` as stock_id,SUM(`unit_price`*`qty_invoiced`) as price,SUM(`qty_invoiced`) as received_qty FROM `purch_order_details` GROUP BY `item_code` )pod
			ON pod.stock_id = item.stock_id
    		"));

    	}

		else if($type !='all' && $location !='all'){
    	
    	$data = DB::select(DB::raw("SELECT item.stock_id,item.description,COALESCE(sp.price,0) as retail_price,COALESCE(sm.qty,0) as available_qty,COALESCE(pod.received_qty,0) as received_qty,COALESCE(pod.price,0) as cost_amount 
			FROM(SELECT * FROM stock_master WHERE category_id='$type')item

			LEFT JOIN(SELECT stock_id,price FROM sale_prices WHERE sales_type_id = 1)sp
			ON sp.stock_id = item.stock_id

			LEFT JOIN(SELECT stock_id,sum(qty)as qty FROM stock_moves WHERE loc_code = '$location' GROUP BY stock_id)sm
			ON sm.stock_id = item.stock_id

			LEFT JOIN(SELECT `item_code` as stock_id,SUM(`unit_price`*`qty_invoiced`) as price,SUM(`qty_invoiced`) as received_qty FROM `purch_order_details` GROUP BY `item_code` )pod
			ON pod.stock_id = item.stock_id
    		"));

    	}

    	return $data;
    }

  public function getSalesReport($type, $from, $to, $year, $month, $item, $customer, $location)
  	{
  	$from = DbDateFormat($from);
  	$to = DbDateFormat($to);

  	$whereConditions = '';
  	if($customer != 'all'){
  		$whereConditions .= " AND sales_orders.debtor_no = '$customer'";
  	}
  	if($item != 'all'){
  		$whereConditions .= " AND infos.stock_id = '$item'";
  	}
  	if($location != 'all'){
  		$whereConditions .= " AND sales_orders.from_stk_loc = '$location'";
  	}


  	if( $type=='daily' ){
  		 // Daily End Here
$data = DB::select(DB::raw("SELECT sales_orders.ord_date,count(infos.order_no) as total_order,SUM(infos.qty) as qty,SUM(infos.purchase) as purchase,SUM(infos.sale) as sale,infos.stock_id FROM sales_orders
				LEFT JOIN(SELECT sale_purch_detail.order_no,sale_purch_detail.stock_id, count(sale_purch_detail.order_no)as total_order,SUM(sale_purch_detail.quantity)as qty,SUM(sale_purch_detail.sale) as sale,SUM(sale_purch_detail.purchase)as purchase FROM(SELECT sales.*,(purchase.purchase_rate*sales.quantity)as purchase FROM(SELECT sods.order_no,sods.stock_id,sods.quantity,(sods.discount_price-(sods.discount_price*item_tax_types.tax_rate/100)) as sale FROM(SELECT sod.`id`, sod.`order_no`, sod.`stock_id`, sod.`unit_price`*sod.`quantity`-(sod.`unit_price`*sod.`quantity`*`discount_percent`)/100 as discount_price ,sod.`discount_percent`,sod.`quantity`,sod.`unit_price`,sod.`tax_type_id` FROM `sales_order_details` as sod WHERE `trans_type`=202)sods
				LEFT JOIN item_tax_types
				ON item_tax_types.id = sods.tax_type_id)sales

				LEFT JOIN(SELECT pods.item_code as stock_id,(pods.price

				+(pods.price*item_tax_types.tax_rate/100))/pods.qty as 

				purchase_rate FROM(SELECT pod.`item_code`,SUM(pod.`unit_price`*pod.`quantity_received`) as price,SUM(pod.`quantity_received`)as qty,pod.`tax_type_id` FROM `purch_order_details` as pod
				GROUP BY pod.item_code)pods

				LEFT JOIN item_tax_types
				ON pods.`tax_type_id` = item_tax_types.id) as purchase

				ON purchase.stock_id = sales.stock_id)sale_purch_detail
				GROUP BY sale_purch_detail.order_no)infos
				ON infos.order_no = sales_orders.order_no
				WHERE sales_orders.trans_type = 202 
				$whereConditions
				GROUP BY sales_orders.ord_date 
				ORDER BY sales_orders.ord_date DESC
				"));
  		 // Daily End Here
  	}else if( $type=='monthly' ){

   			$data = DB::select(DB::raw("SELECT sales_orders.ord_date,count(infos.order_no) as total_order,SUM(infos.qty) as qty,SUM(infos.purchase) as purchase,SUM(infos.sale) as sale,infos.stock_id FROM sales_orders
				LEFT JOIN(SELECT sale_purch_detail.order_no,sale_purch_detail.stock_id, count(sale_purch_detail.order_no)as total_order,SUM(sale_purch_detail.quantity)as qty,SUM(sale_purch_detail.sale) as sale,SUM(sale_purch_detail.purchase)as purchase FROM(SELECT sales.*,(purchase.purchase_rate*sales.quantity)as purchase FROM(SELECT sods.order_no,sods.stock_id,sods.quantity,(sods.discount_price-(sods.discount_price*item_tax_types.tax_rate/100)) as sale FROM(SELECT sod.`id`, sod.`order_no`, sod.`stock_id`, sod.`unit_price`*sod.`quantity`-(sod.`unit_price`*sod.`quantity`*`discount_percent`)/100 as discount_price ,sod.`discount_percent`,sod.`quantity`,sod.`unit_price`,sod.`tax_type_id` FROM `sales_order_details` as sod WHERE `trans_type`=202)sods
				LEFT JOIN item_tax_types
				ON item_tax_types.id = sods.tax_type_id)sales

				LEFT JOIN(SELECT pods.item_code as stock_id,(pods.price

				+(pods.price*item_tax_types.tax_rate/100))/pods.qty as 

				purchase_rate FROM(SELECT pod.`item_code`,SUM(pod.`unit_price`*pod.`quantity_received`) as price,SUM(pod.`quantity_received`)as qty,pod.`tax_type_id` FROM `purch_order_details` as pod
				GROUP BY pod.item_code)pods

				LEFT JOIN item_tax_types
				ON pods.`tax_type_id` = item_tax_types.id) as purchase

				ON purchase.stock_id = sales.stock_id)sale_purch_detail
				GROUP BY sale_purch_detail.order_no)infos
				ON infos.order_no = sales_orders.order_no
				WHERE sales_orders.trans_type = 202

				$whereConditions

                AND YEAR(sales_orders.ord_date) = YEAR(NOW())
                GROUP BY MONTH(sales_orders.ord_date)
				ORDER BY sales_orders.ord_date DESC
				"));

  	}else if( $type=='yearly' ){
  		//d($type,1);
  		if( $year=='all' ){
   			$data = DB::select(DB::raw("SELECT sales_orders.ord_date,count(infos.order_no) as total_order,SUM(infos.qty) as qty,SUM(infos.purchase) as purchase,SUM(infos.sale) as sale,infos.stock_id FROM sales_orders
				LEFT JOIN(SELECT sale_purch_detail.order_no,sale_purch_detail.stock_id, count(sale_purch_detail.order_no)as total_order,SUM(sale_purch_detail.quantity)as qty,SUM(sale_purch_detail.sale) as sale,SUM(sale_purch_detail.purchase)as purchase FROM(SELECT sales.*,(purchase.purchase_rate*sales.quantity)as purchase FROM(SELECT sods.order_no,sods.stock_id,sods.quantity,(sods.discount_price-(sods.discount_price*item_tax_types.tax_rate/100)) as sale FROM(SELECT sod.`id`, sod.`order_no`, sod.`stock_id`, sod.`unit_price`*sod.`quantity`-(sod.`unit_price`*sod.`quantity`*`discount_percent`)/100 as discount_price ,sod.`discount_percent`,sod.`quantity`,sod.`unit_price`,sod.`tax_type_id` FROM `sales_order_details` as sod WHERE `trans_type`=202)sods
				LEFT JOIN item_tax_types
				ON item_tax_types.id = sods.tax_type_id)sales

				LEFT JOIN(SELECT pods.item_code as stock_id,(pods.price

				+(pods.price*item_tax_types.tax_rate/100))/pods.qty as 

				purchase_rate FROM(SELECT pod.`item_code`,SUM(pod.`unit_price`*pod.`quantity_received`) as price,SUM(pod.`quantity_received`)as qty,pod.`tax_type_id` FROM `purch_order_details` as pod
				GROUP BY pod.item_code)pods

				LEFT JOIN item_tax_types
				ON pods.`tax_type_id` = item_tax_types.id) as purchase

				ON purchase.stock_id = sales.stock_id)sale_purch_detail
				GROUP BY sale_purch_detail.order_no)infos
				ON infos.order_no = sales_orders.order_no
				WHERE sales_orders.trans_type = 202
				$whereConditions
				GROUP BY YEAR(sales_orders.ord_date)
				ORDER BY sales_orders.ord_date DESC
				"));
  		}elseif ($year !='all') {
  			if( $month=='all' ){
   			$data = DB::select(DB::raw("SELECT sales_orders.ord_date,count(infos.order_no) as total_order,SUM(infos.qty) as qty,SUM(infos.purchase) as purchase,SUM(infos.sale) as sale,infos.stock_id FROM sales_orders
				LEFT JOIN(SELECT sale_purch_detail.order_no,sale_purch_detail.stock_id, count(sale_purch_detail.order_no)as total_order,SUM(sale_purch_detail.quantity)as qty,SUM(sale_purch_detail.sale) as sale,SUM(sale_purch_detail.purchase)as purchase FROM(SELECT sales.*,(purchase.purchase_rate*sales.quantity)as purchase FROM(SELECT sods.order_no,sods.stock_id,sods.quantity,(sods.discount_price-(sods.discount_price*item_tax_types.tax_rate/100)) as sale FROM(SELECT sod.`id`, sod.`order_no`, sod.`stock_id`, sod.`unit_price`*sod.`quantity`-(sod.`unit_price`*sod.`quantity`*`discount_percent`)/100 as discount_price ,sod.`discount_percent`,sod.`quantity`,sod.`unit_price`,sod.`tax_type_id` FROM `sales_order_details` as sod WHERE `trans_type`=202)sods
				LEFT JOIN item_tax_types
				ON item_tax_types.id = sods.tax_type_id)sales

				LEFT JOIN(SELECT pods.item_code as stock_id,(pods.price

				+(pods.price*item_tax_types.tax_rate/100))/pods.qty as 

				purchase_rate FROM(SELECT pod.`item_code`,SUM(pod.`unit_price`*pod.`quantity_received`) as price,SUM(pod.`quantity_received`)as qty,pod.`tax_type_id` FROM `purch_order_details` as pod
				GROUP BY pod.item_code)pods

				LEFT JOIN item_tax_types
				ON pods.`tax_type_id` = item_tax_types.id) as purchase

				ON purchase.stock_id = sales.stock_id)sale_purch_detail
				GROUP BY sale_purch_detail.order_no)infos
				ON infos.order_no = sales_orders.order_no
				WHERE sales_orders.trans_type = 202
				$whereConditions
                AND YEAR(sales_orders.ord_date) = '$year'
                GROUP BY MONTH(sales_orders.ord_date)
				ORDER BY sales_orders.ord_date DESC
				")); 
  		}else if( $month !='all'){

   			$data = DB::select(DB::raw("SELECT sales_orders.ord_date,count(infos.order_no) as total_order,SUM(infos.qty) as qty,SUM(infos.purchase) as purchase,SUM(infos.sale) as sale,infos.stock_id FROM sales_orders
				LEFT JOIN(SELECT sale_purch_detail.order_no,sale_purch_detail.stock_id, count(sale_purch_detail.order_no)as total_order,SUM(sale_purch_detail.quantity)as qty,SUM(sale_purch_detail.sale) as sale,SUM(sale_purch_detail.purchase)as purchase FROM(SELECT sales.*,(purchase.purchase_rate*sales.quantity)as purchase FROM(SELECT sods.order_no,sods.stock_id,sods.quantity,(sods.discount_price-(sods.discount_price*item_tax_types.tax_rate/100)) as sale FROM(SELECT sod.`id`, sod.`order_no`, sod.`stock_id`, sod.`unit_price`*sod.`quantity`-(sod.`unit_price`*sod.`quantity`*`discount_percent`)/100 as discount_price ,sod.`discount_percent`,sod.`quantity`,sod.`unit_price`,sod.`tax_type_id` FROM `sales_order_details` as sod WHERE `trans_type`=202)sods
				LEFT JOIN item_tax_types
				ON item_tax_types.id = sods.tax_type_id)sales

				LEFT JOIN(SELECT pods.item_code as stock_id,(pods.price

				+(pods.price*item_tax_types.tax_rate/100))/pods.qty as 

				purchase_rate FROM(SELECT pod.`item_code`,SUM(pod.`unit_price`*pod.`quantity_received`) as price,SUM(pod.`quantity_received`)as qty,pod.`tax_type_id` FROM `purch_order_details` as pod
				GROUP BY pod.item_code)pods

				LEFT JOIN item_tax_types
				ON pods.`tax_type_id` = item_tax_types.id) as purchase

				ON purchase.stock_id = sales.stock_id)sale_purch_detail
				GROUP BY sale_purch_detail.order_no)infos
				ON infos.order_no = sales_orders.order_no
				WHERE sales_orders.trans_type = 202
				$whereConditions
                AND YEAR(sales_orders.ord_date) = '$year'
                AND MONTH(sales_orders.ord_date) = '$month'
                GROUP BY sales_orders.ord_date
				ORDER BY sales_orders.ord_date DESC
				"));
  			}
  		}

  	}elseif($type=='custom'){
   			$data = DB::select(DB::raw("SELECT sales_orders.ord_date,count(infos.order_no) as total_order,SUM(infos.qty) as qty,SUM(infos.purchase) as purchase,SUM(infos.sale) as sale,infos.stock_id FROM sales_orders
				LEFT JOIN(SELECT sale_purch_detail.order_no,sale_purch_detail.stock_id, count(sale_purch_detail.order_no)as total_order,SUM(sale_purch_detail.quantity)as qty,SUM(sale_purch_detail.sale) as sale,SUM(sale_purch_detail.purchase)as purchase FROM(SELECT sales.*,(purchase.purchase_rate*sales.quantity)as purchase FROM(SELECT sods.order_no,sods.stock_id,sods.quantity,(sods.discount_price-(sods.discount_price*item_tax_types.tax_rate/100)) as sale FROM(SELECT sod.`id`, sod.`order_no`, sod.`stock_id`, sod.`unit_price`*sod.`quantity`-(sod.`unit_price`*sod.`quantity`*`discount_percent`)/100 as discount_price ,sod.`discount_percent`,sod.`quantity`,sod.`unit_price`,sod.`tax_type_id` FROM `sales_order_details` as sod WHERE `trans_type`=202)sods
				LEFT JOIN item_tax_types
				ON item_tax_types.id = sods.tax_type_id)sales

				LEFT JOIN(SELECT pods.item_code as stock_id,(pods.price

				+(pods.price*item_tax_types.tax_rate/100))/pods.qty as 

				purchase_rate FROM(SELECT pod.`item_code`,SUM(pod.`unit_price`*pod.`quantity_received`) as price,SUM(pod.`quantity_received`)as qty,pod.`tax_type_id` FROM `purch_order_details` as pod
				GROUP BY pod.item_code)pods

				LEFT JOIN item_tax_types
				ON pods.`tax_type_id` = item_tax_types.id) as purchase

				ON purchase.stock_id = sales.stock_id)sale_purch_detail
				GROUP BY sale_purch_detail.order_no)infos
				ON infos.order_no = sales_orders.order_no
				WHERE sales_orders.trans_type = 202
				$whereConditions
                AND sales_orders.ord_date BETWEEN '$from' AND '$to'
                GROUP BY sales_orders.ord_date
				ORDER BY sales_orders.ord_date DESC
				"));    	
	}
    	return $data;

    }

    public function getSalesReportByDate($date){
           $data = DB::select(DB::raw("SELECT info_tbl.*,dm.name FROM(SELECT final_tbl.ord_date,final_tbl.order_reference,final_tbl.debtor_no,final_tbl.order_reference_id, SUM(final_tbl.quantity) as qty,SUM(final_tbl.sales_price) as sales_price_total,SUM(final_tbl.tax_amount)as tax,SUM(final_tbl.purchase_price) as purch_price_amount FROM(SELECT sod.*,so.ord_date,so.order_reference,so.debtor_no,so.order_reference_id,(sod.quantity*sod.unit_price-sod.quantity*sod.unit_price*sod.discount_percent/100) as sales_price,(sod.quantity*sod.unit_price*tax.tax_rate/100) as tax_amount,purchase_table.rate as purchase_unit_price,(sod.quantity*purchase_table.rate) as purchase_price FROM(SELECT sales_order_details.order_no,sales_order_details.stock_id,sales_order_details.`quantity`,sales_order_details.`unit_price`,sales_order_details.`discount_percent`,sales_order_details.`tax_type_id` as tax_id FROM `sales_order_details` WHERE `trans_type`=202)sod
				LEFT JOIN item_tax_types as tax
				ON tax.id = sod.tax_id

				LEFT JOIN(SELECT pods.item_code as stock_id, pods.qty, pods.total, (pods.total/pods.qty) as rate FROM(SELECT pod.item_code, SUM(pod.qty) as qty, SUM(pod.total) as total FROM(SELECT purch_order_details.`item_code`,purch_order_details.`quantity_received` as qty,(purch_order_details.`quantity_received`*purch_order_details.`unit_price`+(purch_order_details.`quantity_received`*purch_order_details.`unit_price`*item_tax_types.tax_rate/100)) as total FROM `purch_order_details` LEFT JOIN item_tax_types ON item_tax_types.id = purch_order_details.`tax_type_id`)pod GROUP BY pod.item_code)pods)purchase_table
				ON purchase_table.stock_id = sod.stock_id

				LEFT JOIN (SELECT * FROM sales_orders) as so
				ON so.order_no = sod.order_no
				WHERE so.ord_date = '$date')final_tbl
				GROUP BY final_tbl.order_no)info_tbl

				LEFT JOIN debtors_master as dm
				ON dm.debtor_no = info_tbl.debtor_no
				"));
		return $data;
    }

    public function getSalesHistoryReport($from,$to,$user){
    	if($from == NULL || $to == NULL || $user == NULL){
    	        $data = DB::select(DB::raw("SELECT info_tbl.*,dm.name FROM(SELECT final_tbl.ord_date,final_tbl.order_reference,final_tbl.debtor_no,final_tbl.order_no,final_tbl.order_reference_id, SUM(final_tbl.quantity) as qty,SUM(final_tbl.sales_price) as sales_price_total,SUM(final_tbl.tax_amount)as tax,SUM(final_tbl.purchase_price) as purch_price_amount FROM(SELECT sod.*,so.ord_date,so.order_reference,so.debtor_no,so.order_reference_id,(sod.quantity*sod.unit_price-sod.quantity*sod.unit_price*sod.discount_percent/100) as sales_price,(sod.quantity*sod.unit_price*tax.tax_rate/100) as tax_amount,purchase_table.rate as purchase_unit_price,(sod.quantity*purchase_table.rate) as purchase_price FROM(SELECT sales_order_details.order_no,sales_order_details.stock_id,sales_order_details.`quantity`,sales_order_details.`unit_price`,sales_order_details.`discount_percent`,sales_order_details.`tax_type_id` as tax_id FROM `sales_order_details` WHERE `trans_type`=202)sod
				LEFT JOIN item_tax_types as tax
				ON tax.id = sod.tax_id

				LEFT JOIN(SELECT pods.item_code as stock_id, pods.qty as purchase_qty, pods.total as price, (pods.total/pods.qty) as rate FROM(SELECT pod.item_code, SUM(pod.qty) as qty, SUM(pod.total) as total FROM(SELECT purch_order_details.`item_code`,purch_order_details.`quantity_received` as qty,(purch_order_details.`quantity_received`*purch_order_details.`unit_price`+(purch_order_details.`quantity_received`*purch_order_details.`unit_price`*item_tax_types.tax_rate/100)) as total FROM `purch_order_details` LEFT JOIN item_tax_types ON item_tax_types.id = purch_order_details.`tax_type_id`)pod GROUP BY pod.item_code)pods)purchase_table
				ON purchase_table.stock_id = sod.stock_id

				LEFT JOIN (SELECT * FROM sales_orders) as so
				ON so.order_no = sod.order_no)final_tbl
				GROUP BY final_tbl.order_no)info_tbl

				LEFT JOIN debtors_master as dm
				ON dm.debtor_no = info_tbl.debtor_no
				ORDER BY info_tbl.ord_date DESC

				"));
			}else if($user == 'all' && $from != NULL && $to != NULL){
    	        $data = DB::select(DB::raw("SELECT info_tbl.*,dm.name FROM(SELECT final_tbl.ord_date,final_tbl.order_reference,final_tbl.debtor_no,final_tbl.order_no,final_tbl.order_reference_id, SUM(final_tbl.quantity) as qty,SUM(final_tbl.sales_price) as sales_price_total,SUM(final_tbl.tax_amount)as tax,SUM(final_tbl.purchase_price) as purch_price_amount FROM(SELECT sod.*,so.ord_date,so.order_reference,so.debtor_no,so.order_reference_id,(sod.quantity*sod.unit_price-sod.quantity*sod.unit_price*sod.discount_percent/100) as sales_price,(sod.quantity*sod.unit_price*tax.tax_rate/100) as tax_amount,purchase_table.rate as purchase_unit_price,(sod.quantity*purchase_table.rate) as purchase_price FROM(SELECT sales_order_details.order_no,sales_order_details.stock_id,sales_order_details.`quantity`,sales_order_details.`unit_price`,sales_order_details.`discount_percent`,sales_order_details.`tax_type_id` as tax_id FROM `sales_order_details` WHERE `trans_type`=202)sod
				LEFT JOIN item_tax_types as tax
				ON tax.id = sod.tax_id

				LEFT JOIN(SELECT pods.item_code as stock_id, pods.qty as purchase_qty, pods.total as price, (pods.total/pods.qty) as rate FROM(SELECT pod.item_code, SUM(pod.qty) as qty, SUM(pod.total) as total FROM(SELECT purch_order_details.`item_code`,purch_order_details.`quantity_received` as qty,(purch_order_details.`quantity_received`*purch_order_details.`unit_price`+(purch_order_details.`quantity_received`*purch_order_details.`unit_price`*item_tax_types.tax_rate/100)) as total FROM `purch_order_details` LEFT JOIN item_tax_types ON item_tax_types.id = purch_order_details.`tax_type_id`)pod GROUP BY pod.item_code)pods)purchase_table
				ON purchase_table.stock_id = sod.stock_id

				LEFT JOIN (SELECT * FROM sales_orders) as so
				ON so.order_no = sod.order_no)final_tbl
				GROUP BY final_tbl.order_no)info_tbl

				LEFT JOIN debtors_master as dm
				ON dm.debtor_no = info_tbl.debtor_no

				WHERE info_tbl.ord_date BETWEEN '$from' AND '$to'
				ORDER BY info_tbl.ord_date DESC

				"));
			}else if($user != 'all' && $from != NULL && $to != NULL){
    	        $data = DB::select(DB::raw("SELECT info_tbl.*,dm.name FROM(SELECT final_tbl.ord_date,final_tbl.order_reference,final_tbl.debtor_no,final_tbl.order_no,final_tbl.order_reference_id, SUM(final_tbl.quantity) as qty,SUM(final_tbl.sales_price) as sales_price_total,SUM(final_tbl.tax_amount)as tax,SUM(final_tbl.purchase_price) as purch_price_amount FROM(SELECT sod.*,so.ord_date,so.order_reference,so.debtor_no,so.order_reference_id,(sod.quantity*sod.unit_price-sod.quantity*sod.unit_price*sod.discount_percent/100) as sales_price,(sod.quantity*sod.unit_price*tax.tax_rate/100) as tax_amount,purchase_table.rate as purchase_unit_price,(sod.quantity*purchase_table.rate) as purchase_price FROM(SELECT sales_order_details.order_no,sales_order_details.stock_id,sales_order_details.`quantity`,sales_order_details.`unit_price`,sales_order_details.`discount_percent`,sales_order_details.`tax_type_id` as tax_id FROM `sales_order_details` WHERE `trans_type`=202)sod
				LEFT JOIN item_tax_types as tax
				ON tax.id = sod.tax_id

				LEFT JOIN(SELECT pods.item_code as stock_id, pods.qty as purchase_qty, pods.total as price, (pods.total/pods.qty) as rate FROM(SELECT pod.item_code, SUM(pod.qty) as qty, SUM(pod.total) as total FROM(SELECT purch_order_details.`item_code`,purch_order_details.`quantity_received` as qty,(purch_order_details.`quantity_received`*purch_order_details.`unit_price`+(purch_order_details.`quantity_received`*purch_order_details.`unit_price`*item_tax_types.tax_rate/100)) as total FROM `purch_order_details` LEFT JOIN item_tax_types ON item_tax_types.id = purch_order_details.`tax_type_id`)pod GROUP BY pod.item_code)pods)purchase_table
				ON purchase_table.stock_id = sod.stock_id

				LEFT JOIN (SELECT * FROM sales_orders) as so
				ON so.order_no = sod.order_no)final_tbl
				GROUP BY final_tbl.order_no)info_tbl

				LEFT JOIN debtors_master as dm
				ON dm.debtor_no = info_tbl.debtor_no

				WHERE info_tbl.ord_date BETWEEN '$from' AND '$to'
				AND info_tbl.debtor_no = '$user'
				ORDER BY info_tbl.ord_date DESC

				"));
			}
			
		return $data;
    }
    // Get profit and sale and cost for last 30 days
    public function getSalesCostProfit(){
    	$from = date('Y-m-d', strtotime('-30 days'));
    	$to = date('Y-m-d');
    	
		$data = DB::select(DB::raw("SELECT scp.* FROM(SELECT info_tbl.ord_date, SUM(info_tbl.sales_price_total) as sale ,SUM(info_tbl.purch_price_amount) as cost,SUM(info_tbl.sales_price_total-info_tbl.purch_price_amount)as profit FROM(SELECT final_tbl.ord_date,SUM(final_tbl.sales_price) as sales_price_total,SUM(final_tbl.purchase_price) as purch_price_amount FROM(SELECT sod.*,so.ord_date,so.order_reference,so.debtor_no,(sod.quantity*sod.unit_price-sod.quantity*sod.unit_price*sod.discount_percent/100) as sales_price,(sod.quantity*sod.unit_price*tax.tax_rate/100) as tax_amount,purchase_table.rate as purchase_unit_price,(sod.quantity*purchase_table.rate) as purchase_price FROM(SELECT sales_order_details.order_no,sales_order_details.stock_id,sales_order_details.`quantity`,sales_order_details.`unit_price`,sales_order_details.`discount_percent`,sales_order_details.`tax_type_id` as tax_id FROM `sales_order_details` WHERE `trans_type`=202)sod
		LEFT JOIN item_tax_types as tax
		ON tax.id = sod.tax_id

		LEFT JOIN(SELECT purchase_info.*,(purchase_info.price/purchase_info.purchase_qty) as rate FROM(SELECT purch_tbl.item_code as stock_id,SUM(purch_tbl.quantity_received) as purchase_qty,SUM(purch_tbl.price) as price FROM(SELECT pod.`item_code`,pod.`quantity_received`,pod.`unit_price`,(pod.`unit_price`*pod.`quantity_received`) as price FROM `purch_order_details` as pod)purch_tbl GROUP BY purch_tbl.item_code)purchase_info)purchase_table
		ON purchase_table.stock_id = sod.stock_id

		LEFT JOIN (SELECT * FROM sales_orders) as so
		ON so.order_no = sod.order_no)final_tbl
		GROUP BY final_tbl.order_no)info_tbl
        GROUP BY info_tbl.ord_date)scp

	    WHERE scp.ord_date BETWEEN '$from' AND '$to'
		"));
    	return $data;
    }

    public function orderToInvoiceList(){
      	$data = DB::select(DB::raw("SELECT so.*,dm.name,COALESCE(sm.qty,0)as inv_qty FROM(SELECT so.order_no,so.reference,so.ord_date,so.debtor_no,SUM(sod.quantity)as ord_qty FROM `sales_orders` as so LEFT JOIN sales_order_details as sod ON sod.order_no = so.order_no WHERE so.`trans_type` = 201 GROUP BY sod.order_no )so
				LEFT JOIN(SELECT `order_no`,SUM(qty)as qty FROM `stock_moves` WHERE `trans_type`=202 GROUP BY `order_no` )sm
				ON 
				sm.order_no = so.order_no
				LEFT JOIN debtors_master as dm 
				ON
				dm.`debtor_no` = so.`debtor_no`
				ORDER BY so.ord_date DESC"
				//ORDER BY 
				));
      	//d($data,1);
       return $data;    	
    }
    public function orderToshipmentList(){
    	$data = DB::select(DB::raw("SELECT so.*,dm.name FROM(SELECT order_no,reference,ord_date,debtor_no FROM `sales_orders` WHERE `trans_type`=201 AND `order_reference_id` = 0)so LEFT JOIN(SELECT * FROM `shipment` WHERE status=1 GROUP BY order_no)sp ON sp.order_no = so.order_no LEFT JOIN debtors_master as dm ON dm.`debtor_no` = so.`debtor_no` WHERE sp.order_no IS NULL ORDER BY so.ord_date DESC"));
    	return $data;
    }
    public function latestInvoicesList(){
    	$data = DB::table('sales_orders')
    			->leftjoin("debtors_master",'debtors_master.debtor_no','=','sales_orders.debtor_no')
    			->where('sales_orders.trans_type',SALESINVOICE)
    			->orderBy('sales_orders.ord_date','desc')
    			->select('sales_orders.order_reference_id as order_no','sales_orders.order_no as invoice_no','sales_orders.order_reference','sales_orders.reference','debtors_master.name','sales_orders.total','sales_orders.ord_date')
    			->take(5)
    			->get();

    	return $data;
    }
    public function latestPaymentList(){
        $data = DB::table('payment_history')
                             ->leftjoin('debtors_master','debtors_master.debtor_no','=','payment_history.customer_id')
                             ->leftjoin('payment_terms','payment_terms.id','=','payment_history.payment_type_id')
                             ->leftjoin('sales_orders','sales_orders.reference','=','payment_history.invoice_reference')
                             ->select('payment_history.*','debtors_master.name','payment_terms.name as pay_type','sales_orders.order_no as invoice_id','sales_orders.order_reference_id as order_id')
                             ->orderBy('payment_date','DESC')
                             ->take(5)
                             ->get();
        return $data;
    }

}
