<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;

class SalesTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'finance';
        $data['list_menu'] = 'sales-type';
        $data['salesTypeData'] = DB::table('sales_types')->get();
        
        return view('admin.salestype.sale_type_list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'sales_type' => 'required|min:2',
            'tax_included' => 'required',
        ]);

        $data['sales_type'] = $request->sales_type;
        $data['tax_included'] = $request->tax_included;
        $data['defaults'] = $request->defaults;

        if($request->defaults == 1) {
            DB::table('sales_types')->where('defaults', 1)->update(['defaults'=>0]);
        }

        $id = \DB::table('sales_types')->insertGetId($data);

        if (!empty($id)) {

            \Session::flash('success',trans('message.success.save_success'));
            return redirect()->intended('sales-type');
        } else {

            return back()->withInput()->withErrors(['email' => "Invalid Request !"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $id = $_POST['id'];

        $taxData = \DB::table('sales_types')->where('id', $id)->first();

        $return_arr['sales_type'] = $taxData->sales_type;
        $return_arr['tax_included'] = $taxData->tax_included;
        $return_arr['id'] = $taxData->id;
        $return_arr['defaults'] = $taxData->defaults;

        echo json_encode($return_arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'sales_type' => 'required|min:2',
            'tax_included' => 'required',
            'type_id' => 'required',
        ]);

        
        $data['sales_type'] = $request->sales_type;
        $data['tax_included'] = $request->tax_included;
        $id = $request->type_id;
        $data['defaults'] = $request->defaults;

        if($request->defaults == 1) {
            DB::table('sales_types')->where('defaults', 1)->update(['defaults'=>0]);
        }
        

        \DB::table('sales_types')->where('id', $id)->update($data);

        \Session::flash('success',trans('message.success.update_success'));
            return redirect()->intended('sales-type');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (isset($id)) {
            $record = \DB::table('sales_types')->where('id', $id)->first();
            if ($record) {
                
                \DB::table('sales_types')->where('id', '=', $id)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('sales-type');
            }
        }
    }
}
