<?php

namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	protected $table = 'item_code';

    public function getAllItem()
    { 
      
     // $data =  DB::select("SELECT stock_category.description as cname, item_code.description as name,item_code.item_image,item_code.inactive,item_code.id,item_code.stock_id,stock_master.long_description,stock_master.units,item_tax_types.name as tax_type FROM `item_code` left join stock_master on item_code.stock_id=stock_master.stock_id left Join stock_category on item_code.category_id=stock_category.category_id left Join item_tax_types on stock_master.tax_type_id = item_tax_types.id where item_code.deleted_status = 0 ORDER BY item_code.id DESC");
      $data = DB::select(DB::raw("SELECT item.id as item_id,item.inactive,item.stock_id,item.description,item.category_id,item.item_image as img,pp.price as purchase_price,sc.description as category_name,COALESCE
              (sm.item_qty,0) as item_qty,sph.price as whole_sale_price,spr.price as retail_sale_price

              FROM (SELECT * FROM item_code WHERE deleted_status = 0)item 

              LEFT JOIN purchase_prices as pp
               ON pp.stock_id = item.stock_id 

              LEFT JOIN stock_category as sc 
               ON sc.category_id = item.category_id

              LEFT JOIN(SELECT stock_id,sum(qty) as item_qty FROM stock_moves GROUP BY stock_id)sm
               ON sm.stock_id = item.stock_id
               
              LEFT JOIN(SELECT stock_id,price FROM sale_prices WHERE sales_type_id = 2)sph
               ON sph.stock_id = item.stock_id 
               
              LEFT JOIN(SELECT stock_id,price FROM sale_prices WHERE sales_type_id = 1)spr
               ON spr.stock_id = item.stock_id

              ORDER BY item.description ASC
               "));
      //d($data,1);
      return $data;
    }

    public function getItemById($id)
    { 
      return $this->where('item_code.id', '=', $id)
      				->leftJoin('stock_master', 'item_code.stock_id', '=', 'stock_master.stock_id')
      				->leftJoin('stock_category', 'item_code.category_id', '=', 'stock_category.category_id')
      				->select('item_code.*', 'stock_master.*','stock_category.description as cname')
      				->first();      
    }

    public function getTransaction($id)
    {
              $data = DB::table('stock_moves')
                     ->select(DB::raw('sum(qty) as total, loc_code'))
                     ->where(['stock_id'=>$id])
                     ->groupBy('loc_code')
                     ->get();
          return $data;
    }

    public function stock_validate($loc,$id)
    {
        $data = DB::table('stock_moves')
                     ->select(DB::raw('sum(qty) as total'))
                     ->where(['stock_id'=>$id, 'loc_code'=>$loc])
                     ->groupBy('loc_code')
                     ->first();

          return $data;
    }

    public function getAllItemCsv()
    {
      $dad =  DB::select("SELECT ic.`stock_id`,ic.description as item_name,sc.description as category,pp.price as purcashe_price,rp.price as retail_price,wsp.price as wholesale_price FROM `item_code` as ic
      LEFT JOIN stock_category as sc
      ON sc.`category_id` = ic.`category_id`

      LEFT JOIN purchase_prices as pp
      ON pp.`stock_id` = ic.`stock_id`

      LEFT JOIN(SELECT * FROM `sale_prices` WHERE `sales_type_id`=1)rp
      ON rp.stock_id = ic.`stock_id`

      LEFT JOIN(SELECT * FROM `sale_prices` WHERE `sales_type_id`=2)wsp
      ON wsp.stock_id = ic.`stock_id`
      WHERE ic.inactive=0 AND ic.deleted_status = 0 
      ");
      return $dad;
    }
}
