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
        $data['menu']          = 'contracts';
        $data['sub_menu']      = 'contract/list';
        $data['contractsData'] = $this->jobContract->getAllJobContract(null, null, null, null, null);

        return view('admin.jobContract.contractList', $data);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderFiltering()
    {
        $data['menu']     = 'contracts';
        $data['sub_menu'] = 'contract/list';

        $data['location'] = $location = isset($_GET['location']) ? $_GET['location'] : null;
        $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $data['item']     = $item = isset($_GET['product']) ? $_GET['product'] : null;

        $data['customerList'] = DB::table('debtors_master')->select('debtor_no', 'name')->where(['inactive' => 0])->get();
        $data['locationList'] = DB::table('location')->select('loc_code', 'location_name')->get();
        $data['productList']  = DB::table('item_code')->where(['inactive' => 0, 'deleted_status' => 0])->select('stock_id', 'description')->get();

        $fromDate = DB::table('job_contracts')->select('contract_date')->where('trans_type', SALESORDER)->orderBy('contract_date', 'asc')->first();

        if (isset($_GET['from'])) {
            $data['from'] = $from = $_GET['from'];
        } else {
            $data['from'] = $from = formatDate(date("d-m-Y", strtotime($fromDate->contract_date)));
        }

        if (isset($_GET['to'])) {
            $data['to'] = $to = $_GET['to'];
        } else {
            $data['to'] = $to = formatDate(date('d-m-Y'));
        }


        $data['contractData'] = $this->jobContract->getAllJobContract($from, $to, $location, $customer, $item);

        return view('admin.jobContract.contractListFilter', $data);
    }


    public function create()
    {
        $data['menu']         = 'contracts';
        $data['sub_menu']     = 'contract/list';
        $data['customerData'] = DB::table('debtors_master')->where(['inactive' => 0])->get();
        $data['locData']      = DB::table('location')->get();

        $data['payments'] = DB::table('payment_terms')->get();

        $data['salesType'] = DB::table('sales_types')->select('sales_type', 'id', 'defaults')->get();
        // d($data['salesType'],1);
        $contract_count = DB::table('job_contracts')->where('trans_type', SALESORDER)->count();

        if ($contract_count > 0) {
            $contractReference      = DB::table('job_contracts')->where('trans_type', SALESORDER)->select('reference')->orderBy('job_contract_no', 'DESC')->first();
            $ref                    = explode("-", $contractReference->reference);
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

    public function store(Request $request)
    {
        $userId = \Auth::user()->id;
        $this->validate($request, [
            'reference'     => 'required|unique:job_contracts',
            'from_stk_loc'  => 'required',
            'contract_date' => 'required',
            'debtor_no'     => 'required',
            'branch_id'     => 'required',
            'payment_id'    => 'required',
            'item_quantity' => 'required',
        ]);

//d(SALESORDER,1);
        $itemQuantity = $request->item_quantity;
        $itemDiscount = $request->discount;
        $taxIds       = $request->tax_id;
        $unitPrice    = $request->unit_price;
        $description  = $request->description;
        $arrayCount   = $request->arrayCount;

        // create salesOrder
        $jobContract['debtor_no']        = $request->debtor_no;
        $jobContract['branch_id']        = $request->branch_id;
        $jobContract['payment_id']       = $request->payment_id;
        $jobContract['person_id']        = $userId;
        $jobContract['reference']        = $request->reference;
        $jobContract['comments']         = $request->comments;
        $jobContract['trans_type']       = SALESORDER;
        $jobContract['contract_date']    = DbDateFormat($request->contract_date);
        $jobContract['from_stk_loc']     = $request->from_stk_loc;
        $jobContract['total']            = $request->total;
        $jobContract['discount_type']    = $request->discount_type;
        $jobContract['discount_percent'] = $request->perOrderDiscount;
        $jobContract['created_at']       = date('Y-m-d H:i:s');
        // d($salesOrder,1);
        $jobContractId = \DB::table('job_contracts')->insertGetId($jobContract);


        for ($i = 0; $i < count($arrayCount); $i++) {

            // create job contract details
            $jobContractDetail[$i]['job_contract_no']  = $jobContractId;
            $jobContractDetail[$i]['stock_id']         = $jobContractId;
            $jobContractDetail[$i]['description']      = $description[$i];
            $jobContractDetail[$i]['qty_sent']         = 0;
            $jobContractDetail[$i]['quantity']         = $itemQuantity[$i];
            $jobContractDetail[$i]['trans_type']       = SALESORDER;
            $jobContractDetail[$i]['discount_percent'] = $itemDiscount[$i];
            $jobContractDetail[$i]['tax_type_id']      = $taxIds[$i];
            $jobContractDetail[$i]['unit_price']       = $unitPrice[$i];

        }

        for ($i = 0; $i < count($jobContractDetail); $i++) {
            \DB::table('job_contract_details')->insertGetId($jobContractDetail[$i]);
        }

        if (!empty($jobContractId)) {
            \Session::flash('success', trans('message.success.save_success'));
            return redirect()->intended('contract/view-contract-details/' . $jobContractId);
        }
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


    public function viewContractDetails($contractNo)
    {
        $data['menu']     = 'contracts';
        $data['sub_menu'] = 'contract/list';

        $data['taxType']      = $this->sale->calculateTaxRow($contractNo, true);
        $data['customerData'] = DB::table('debtors_master')->get();
        $data['locData']      = DB::table('location')->get();

        $data['contractData']     = DB::table('job_contracts')
            ->where('job_contract_no', '=', $contractNo)
            ->leftJoin('location', 'location.loc_code', '=', 'job_contracts.from_stk_loc')
            ->select("job_contracts.*", "location.location_name")
            ->first();

        $data['invoiceData']   = $this->jobContract->getSalseOrderByID($contractNo, $data['contractData']->from_stk_loc);

        $data['branchs']       = DB::table('cust_branch')->select('debtor_no', 'branch_code', 'br_name')->where('debtor_no', $data['contractData']->debtor_no)->orderBy('br_name', 'ASC')->get();

        $data['payments']      = DB::table('payment_terms')->get();
        $data['invoice_count'] = DB::table('job_contracts')->where('trans_type', SALESINVOICE)->count();

        $data['customerInfo'] = DB::table('job_contracts')
            ->where('job_contracts.job_contract_no', $contractNo)
            ->leftjoin('debtors_master', 'debtors_master.debtor_no', '=', 'job_contracts.debtor_no')
            ->leftjoin('cust_branch', 'cust_branch.branch_code', '=', 'job_contracts.branch_id')
            ->leftjoin('countries', 'countries.id', '=', 'cust_branch.shipping_country_id')
            ->select('debtors_master.debtor_no', 'debtors_master.name', 'debtors_master.phone', 'debtors_master.email', 'cust_branch.br_name', 'cust_branch.br_address', 'cust_branch.billing_street', 'cust_branch.billing_city', 'cust_branch.billing_state', 'cust_branch.billing_zip_code', 'cust_branch.billing_country_id', 'cust_branch.shipping_street', 'cust_branch.shipping_city', 'cust_branch.shipping_state', 'cust_branch.shipping_zip_code', 'cust_branch.shipping_country_id', 'countries.country')
            ->first();

        $data['customer_branch']  = DB::table('cust_branch')->where('branch_code', $data['contractData']->branch_id)->first();
        $data['customer_payment'] = DB::table('payment_terms')->where('id', $data['contractData']->payment_id)->first();

        $data['invoiceList'] = DB::table('job_contracts')
            ->where('contract_reference', $data['contractData']->reference)
            ->select('job_contract_no', 'reference', 'contract_reference', 'total', 'paid_amount')
            ->orderBy('created_at', 'DESC')
            ->get();

        //$data['invoiceQty']     = DB::table('stock_moves')->where(['order_no' => $orderNo, 'trans_type' => SALESINVOICE])->sum('qty');
        \DB::enableQueryLog();

        $fetchInvoiveQty = DB::select(DB::raw("SELECT COALESCE(SUM(jcd.quantity), 0) AS total FROM job_contracts as jc LEFT JOIN job_contract_details as jcd ON jc.job_contract_no=jcd.job_contract_no WHERE jc.job_contract_no='$contractNo' AND jc.trans_type=".SALESINVOICE));

        $data['invoiceQty'] = $fetchInvoiveQty[0]->total;
        $data['contractQty']       = DB::table('job_contract_details')->where(['job_contract_no' => $contractNo, 'trans_type' => SALESORDER])->sum('quantity');
        $data['contractInfo']      = DB::table('job_contracts')->where('job_contract_no', $contractNo)->select('reference', 'job_contract_no')->first();
        $data['paymentsList']   = DB::table('payment_history')
            ->where(['order_reference' => $data['contractInfo']->reference])
            ->leftjoin('payment_terms', 'payment_terms.id', '=', 'payment_history.payment_type_id')
            ->select('payment_history.*', 'payment_terms.name')
            ->orderBy('payment_date', 'DESC')
            ->get();
        $lang                   = Session::get('dflt_lang');
        $data['emailInfo']      = DB::table('email_temp_details')->where(['temp_id' => 5, 'lang' => $lang])->select('subject', 'body')->first();


        return view('admin.jobContract.viewContractDetails', $data);
    }

    /**
     * Check reference no if exists
     */
    public function referenceValidation(Request $request)
    {

        $data   = array();
        $ref    = $request['ref'];
        $result = DB::table('job_contracts')->where("reference", $ref)->first();

        if (count($result) > 0) {
            $data['status_no'] = 1;
        } else {
            $data['status_no'] = 0;
        }

        return json_encode($data);
    }

}
