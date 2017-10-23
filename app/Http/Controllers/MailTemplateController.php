<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

class MailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['menu'] = 'setting';
        $data['list_menu'] = 'mail_temp';
        //$data['tempData'] = DB::table('email_temp')->get();
        
        return view('admin.mailTemp.temp_list', $data);
    }

    public function customerInvTemp($id)
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'mail-temp';
        $data['list_menu'] = 'menu-'.$id;
        $data['tempId'] = $id;
        $data['temp_Data'] = DB::table('email_temp_details')->where('temp_id',$id)->get();
        //d($data,1);
        return view('admin.mailTemp.customer_invoice', $data);
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
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data[] = $request->en;
        $data[] = $request->ar;
        $data[] = $request->ch;
        $data[] = $request->fr;
        $data[] = $request->po;
        $data[] = $request->rh;
        $data[] = $request->sp;
        $data[] = $request->tu;

        $array = $data;
        array_unshift($array,"");
        unset($array[0]);

        for ($i=1; $i < 9 ; $i++) {

            DB::table('email_temp_details')->where([['temp_id',$id],['lang_id', $i]])->update($array[$i]);
        }
        

        \Session::flash('success',trans('message.success.save_success'));
         return redirect()->intended("customer-invoice-temp/$id");
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
