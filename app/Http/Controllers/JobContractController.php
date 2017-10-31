<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\JobContract;
use App\Http\Requests;
use App\Model\Sales;
use App\Model\Shipment;
use DB;
use PDF;
use Session;

class JobContractController extends Controller
{
    public function __construct(JobContract $jobContract, Sales $sales, Shipment $shipment, EmailController $email)
    {
        /**
         * Set the database connection. reference app\helper.php
         */
        //selectDatabase();
        $this->jobContract = $jobContract;
        $this->sale        = $sales;
        $this->shipment    = $shipment;
        $this->email       = $email;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['menu']      = 'sales';
        $data['sub_menu']  = 'contract/list';
        $data['contractsData'] = $this->jobContract->getAllJobContract(null, null, null, null, null);

        return view('admin.jobContract.contractList', $data);
    }

    public function create()
    {
        $data['menu']         = 'sales';
        $data['sub_menu']     = 'contract/list';
        $data['customerData'] = DB::table('debtors_master')->where(['inactive' => 0])->get();
        $data['locData']      = DB::table('location')->get();

        $data['payments'] = DB::table('payment_terms')->get();

        $data['salesType'] = DB::table('sales_types')->select('sales_type', 'id', 'defaults')->get();
        // d($data['salesType'],1);
        $contract_count = DB::table('job_contracts')->where('trans_type', SALESORDER)->count();

        if ($contract_count > 0) {
            $contractReference      = DB::table('job_contracts')->where('trans_type', SALESORDER)->select('reference')->orderBy('job_contract_no', 'DESC')->first();
            $ref                 = explode("-", $contractReference->reference);
            $data['contract_count'] = (int)$ref[1];
        } else {
            $data['contract_count'] = 0;
        }

        $taxTypeList = DB::table('item_tax_types')->get();
        $taxOptions  = '';
        $selectStart = "<select class='form-control taxList' name='tax_id[]'>";
        $selectEnd   = "</select>";

        foreach ($taxTypeList as $key => $value) {
            $taxOptions .= "<option value='" . $value->id . "' taxrate='" . $value->tax_rate . "'>" . $value->name . '(' . $value->tax_rate . ')' . "</option>";
        }

        //$data['tax_type'] = $selectStart . $taxOptions . $selectEnd;
        $data['tax_type'] = $taxOptions;
        //d($data['tax_type'],1);
        return view('admin.jobContract.contractAdd', $data);
    }

    public function store()
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }

    public function viewOrder()
    {

    }

}
