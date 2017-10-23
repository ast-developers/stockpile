<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Category extends Model
{
	protected $table = 'stock_category';

	public function getAllCategory()
    { 
      return $this->leftJoin('item_unit', 'stock_category.dflt_units', '=', 'item_unit.id')
      				->select('stock_category.*', 'item_unit.name')
      				->orderBy('stock_category.category_id', 'desc')
      				->get();      
    }

    public function getAllUnits()
    {
       $data = DB::table('item_unit')->get();

       return $data;
    }

    public function createCategory($data)
    {
        $id = DB::table('stock_category')->insertGetId($data);

        return $id;
    }

    public function getById($id)
    {
        $data = DB::table('stock_category')->where('category_id', $id)->first();

        return $data;
    }

    public function updateCategory($id, $data)
    {
        DB::table('stock_category')->where('category_id', $id)->update($data);
    }
    
}
