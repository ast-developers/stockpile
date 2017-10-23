<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

class TaxController extends Controller
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
        $data['list_menu'] = 'tax';
        $data['taxData'] = DB::table('item_tax_types')->get();
        
        return view('admin.tax.tax_list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'tax';
        $data['header'] = 'tax';

        return view('admin.tax.tax_add', $data);
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
            'name' => 'required|min:2',
            'tax_rate' => 'required',
        ]);

        $data['name'] = $request->name;
        $data['tax_rate'] = $request->tax_rate;
        $data['defaults'] = $request->defaults;

        if($request->defaults == 1) {
            DB::table('item_tax_types')->where('defaults', 1)->update(['defaults'=>0]);
        }

        $id = \DB::table('item_tax_types')->insertGetId($data);

        if (!empty($id)) {

            \Session::flash('success',trans('message.success.save_success'));
            return redirect()->intended('tax');
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

        $taxData = \DB::table('item_tax_types')->where('id', $id)->first();

        $return_arr['name'] = $taxData->name;
        $return_arr['tax_rate'] = $taxData->tax_rate;
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
            'name' => 'required|min:2',
            'tax_id' => 'required',
            'tax_rate' => 'required',
        ]);

        $data['name'] = $request->name;
        $data['tax_rate'] = $request->tax_rate;
        $data['defaults'] = $request->defaults;
        $id = $request->tax_id;

        if($request->defaults == 1) {
            DB::table('item_tax_types')->where('defaults', 1)->update(['defaults'=>0]);
        }
        

        \DB::table('item_tax_types')->where('id', $id)->update($data);

        \Session::flash('success',trans('message.success.update_success'));
            return redirect()->intended('tax');
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
            $record = \DB::table('item_tax_types')->where('id', $id)->first();
            if ($record) {
                
                \DB::table('item_tax_types')->where('id', '=', $id)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('tax');
            }
        }
    }
}
