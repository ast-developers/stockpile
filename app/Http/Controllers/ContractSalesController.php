<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\ContractSales;
use App\Model\Sales;
use DB;
use PDF;

class ContractSalesController extends Controller
{
    public function __construct(ContractSales $contractSales, Sales $sales)
    {
        $this->contractSale = $contractSales;
        $this->sale         = $sales;
    }


    public function index()
    {
        $data['menu']         = 'contracts';
        $data['sub_menu']     = 'contract/direct-invoice';
        $data['contractData'] = $this->contractSale->getAllSalseOrder($from = null, $to = null, $item = null, $customer = null, $location = null);
        return view('admin.contractSale.sales_list', $data);
    }


    public function salesFiltering()
    {
        $data['menu']     = 'contracts';
        $data['sub_menu'] = 'contract/direct-invoice';

        $data['location'] = $location = isset($_GET['location']) ? $_GET['location'] : null;
        $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $data['item']     = $item = isset($_GET['product']) ? $_GET['product'] : null;

        $data['customerList'] = DB::table('debtors_master')->select('debtor_no', 'name')->where(['inactive' => 0])->get();
        $data['locationList'] = DB::table('location')->select('loc_code', 'location_name')->get();

        $fromDate = DB::table('job_contracts')->select('contract_date')->where('trans_type', SALESINVOICE)->orderBy('contract_date', 'asc')->first();

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

        $data['salesData'] = $this->contractSale->getAllSalseOrder($from, $to, $item, $customer, $location);
        return view('admin.contractSale.sales_list_filter', $data);
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
        $invoice_count = DB::table('job_contracts')->where('trans_type', SALESINVOICE)->count();

        if ($invoice_count > 0) {
            $contractReference     = DB::table('job_contracts')->where('trans_type', SALESINVOICE)->select('reference')->orderBy('job_contract_no', 'DESC')->first();
            $ref                   = explode("-", $contractReference->reference);
            $data['invoice_count'] = (int)$ref[1];
        } else {
            $data['invoice_count'] = 0;
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
        return view('admin.contractSale.sale_add', $data);
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

        $itemQuantity = $request->item_quantity;
        $itemDiscount = $request->discount;
        $taxIds       = $request->tax_id;
        $unitPrice    = $request->unit_price;
        $description  = $request->description;
        $arrayCount   = $request->arrayCount;

        // create salesOrder start
        $contractReferenceNo = DB::table('job_contracts')->where('trans_type', SALESORDER)->count();

        if ($contractReferenceNo > 0) {
            $contractReference = DB::table('job_contracts')->where('trans_type', SALESORDER)->select('reference')->orderBy('job_contract_no', 'DESC')->first();
            $ref               = explode("-", $contractReference->reference);
            $contractCount     = (int)$ref[1];
        } else {
            $contractCount = 0;
        }

        $jobContract['debtor_no']     = $request->debtor_no;
        $jobContract['branch_id']     = $request->branch_id;
        $jobContract['payment_id']    = $request->payment_id;
        $jobContract['person_id']     = $userId;
        $jobContract['reference']     = 'JC-' . sprintf("%04d", $contractCount + 1);
        $jobContract['comments']      = $request->comments;
        $jobContract['trans_type']    = SALESORDER;
        $jobContract['contract_date'] = DbDateFormat($request->contract_date);
        $jobContract['from_stk_loc']  = $request->from_stk_loc;
        $jobContract['total']         = $request->total;
        $jobContract['created_at']    = date('Y-m-d H:i:s');
        $jobContractId                = DB::table('job_contracts')->insertGetId($jobContract);
        // create salesOrder end

        // Create salesOrder Invoice start
        $jobContractInvoice['contract_reference_id'] = $jobContractId;
        $jobContractInvoice['contract_reference']    = $jobContract['reference'];
        $jobContractInvoice['trans_type']            = SALESINVOICE;
        $jobContractInvoice['reference']             = $request->reference;
        $jobContractInvoice['debtor_no']             = $request->debtor_no;
        $jobContractInvoice['branch_id']             = $request->branch_id;
        $jobContractInvoice['payment_id']            = $request->payment_id;
        $jobContractInvoice['person_id']             = $userId;
        $jobContractInvoice['comments']              = $request->comments;
        $jobContractInvoice['contract_date']         = DbDateFormat($request->contract_date);
        $jobContractInvoice['from_stk_loc']          = $request->from_stk_loc;
        $jobContractInvoice['total']                 = $request->total;
        $jobContractInvoice['created_at']            = date('Y-m-d H:i:s');

        $contractInvoiceId = DB::table('job_contracts')->insertGetId($jobContractInvoice);
        // Create salesOrder Invoice end

        for ($i = 0; $i < $arrayCount; $i++) {

            // create contractDetail Start
            $contractDetail[$i]['job_contract_no']  = $jobContractId;
            $contractDetail[$i]['stock_id']         = $jobContractId;
            $contractDetail[$i]['description']      = $description[$i];
            $contractDetail[$i]['qty_sent']         = $itemQuantity[$i];
            $contractDetail[$i]['quantity']         = $itemQuantity[$i];
            $contractDetail[$i]['trans_type']       = SALESORDER;
            $contractDetail[$i]['discount_percent'] = $itemDiscount[$i];
            $contractDetail[$i]['tax_type_id']      = $taxIds[$i];
            $contractDetail[$i]['unit_price']       = $unitPrice[$i];

            // Create salesOrderDetailInvoice Start
            $contractDetailInvoice[$i]['job_contract_no']  = $contractInvoiceId;
            $contractDetailInvoice[$i]['stock_id']         = $contractInvoiceId;
            $contractDetailInvoice[$i]['description']      = $description[$i];
            $contractDetailInvoice[$i]['qty_sent']         = $itemQuantity[$i];
            $contractDetailInvoice[$i]['quantity']         = $itemQuantity[$i];
            $contractDetailInvoice[$i]['trans_type']       = SALESINVOICE;
            $contractDetailInvoice[$i]['discount_percent'] = $itemDiscount[$i];
            $contractDetailInvoice[$i]['tax_type_id']      = $taxIds[$i];
            $contractDetailInvoice[$i]['unit_price']       = $unitPrice[$i];
            // Create salesOrderDetailInvoice End

            // create stockMove
            $contractMove[$i]['stock_id']                 = $contractInvoiceId;
            $contractMove[$i]['loc_code']                 = $request->from_stk_loc;
            $contractMove[$i]['tran_date']                = DbDateFormat($request->contract_date);
            $contractMove[$i]['person_id']                = $userId;
            $contractMove[$i]['reference']                = 'store_out_' . $contractInvoiceId;
            $contractMove[$i]['transaction_reference_id'] = $contractInvoiceId;
            $contractMove[$i]['qty']                      = '-' . $itemQuantity[$i];
            $contractMove[$i]['price']                    = $unitPrice[$i];
            $contractMove[$i]['trans_type']               = SALESINVOICE;
            $contractMove[$i]['contract_no']              = $jobContractId;
            $contractMove[$i]['contract_reference']       = $jobContract['reference'];


        }

        for ($i = 0; $i < count($contractDetailInvoice); $i++) {

            DB::table('job_contract_details')->insertGetId($contractDetail[$i]);
            DB::table('job_contract_details')->insertGetId($contractDetailInvoice[$i]);
            DB::table('job_contract_moves')->insertGetId($contractMove[$i]);
        }

        if (!empty($contractInvoiceId)) {
            \Session::flash('success', trans('message.success.save_success'));
            return redirect()->intended('contract/invoice/view-detail-invoice/' . $jobContractId . '/' . $contractInvoiceId);
        }
    }

    /**
     * Show the form for editing the specified resource.
     **/
    public function edit($contractNo)
    {
        $data['menu']         = 'contracts';
        $data['sub_menu']     = 'contract/direct-invoice';
        $data['taxType']      = $this->sale->calculateTaxRow($contractNo, true);
        $data['customerData'] = DB::table('debtors_master')->get();
        $data['locData']      = DB::table('location')->get();
        $data['invoiceData']  = $this->contractSale->getContractSalseInvoiceByID($contractNo);
        $data['contractData'] = DB::table('job_contracts')->where('job_contract_no', '=', $contractNo)->first();
        $data['branchs']      = DB::table('cust_branch')->select('debtor_no', 'branch_code', 'br_name')->where('debtor_no', $data['contractData']->debtor_no)->orderBy('br_name', 'ASC')->get();
        $data['payments']     = DB::table('payment_terms')->get();
        $data['paymentTerms'] = DB::table('invoice_payment_terms')->get();
        $data['inoviceInfo']  = DB::table('job_contracts')->where('job_contract_no', '=', $contractNo)->first();

        return view('admin.contractSale.sale_edit', $data);
    }


    /**
     * Update the specified resource in storage.
     **/
    public function update(Request $request)
    {

        $userId          = \Auth::user()->id;
        $contract_no     = $request->job_contract_no;
        $contract_ref_no = $request->contract_reference_id;
        $this->validate($request, [
            'reference'     => 'required|unique:job_contracts,reference,' . $contract_no . ',job_contract_no',
            'from_stk_loc'  => 'required',
            'contract_date' => 'required',
            'debtor_no'     => 'required',
            'branch_id'     => 'required',
            'payment_id'    => 'required'
        ]);

        $itemQty      = $request->item_quantity;
        $unitPrice    = $request->unit_price;
        $taxIds       = $request->tax_id;
        $itemDiscount = $request->discount;
        $itemPrice    = $request->item_price;
        $description  = $request->description;
        $arrayCount   = $request->arrayCount;

        // update sales_order table
        $jobContract['contract_date'] = DbDateFormat($request->contract_date);
        $jobContract['debtor_no']     = $request->debtor_no;
        $jobContract['trans_type']    = SALESINVOICE;
        $jobContract['branch_id']     = $request->branch_id;
        $jobContract['payment_id']    = $request->payment_id;
        // $jobContract['reference'] = $request->reference;
        $jobContract['from_stk_loc']     = $request->from_stk_loc;
        $jobContract['comments']         = $request->comments;
        $jobContract['total']            = $request->total;
        $jobContract['discount_type']    = $request->discount_type;
        $jobContract['discount_percent'] = $request->perOrderDiscount;
        $jobContract['payment_term']     = $request->payment_term;
        $jobContract['updated_at']       = date('Y-m-d H:i:s');
        // d($jobContract);

        DB::table('job_contracts')->where('job_contract_no', $contract_no)->update($jobContract);

        if (count($itemQty) > 0) {
            for ($i = 0; $i < $arrayCount; $i++) {
                // update sales_order_details table
                $contractDetail[$i]['stock_id']         = $contract_no;
                $contractDetail[$i]['description']      = $description[$i];
                $contractDetail[$i]['unit_price']       = $unitPrice[$i];
                $contractDetail[$i]['qty_sent']         = $itemQty[$i];
                $contractDetail[$i]['trans_type']       = SALESINVOICE;
                $contractDetail[$i]['quantity']         = $itemQty[$i];
                $contractDetail[$i]['discount_percent'] = $itemDiscount[$i];

                // Update stock_move table
                $contractMove[$i]['stock_id']                 = $contract_no;
                $contractMove[$i]['trans_type']               = SALESINVOICE;
                $contractMove[$i]['loc_code']                 = $request->from_stk_loc;
                $contractMove[$i]['tran_date']                = DbDateFormat($request->ord_date);
                $contractMove[$i]['person_id']                = $userId;
                $contractMove[$i]['reference']                = 'store_out_' . $contract_no;
                $contractMove[$i]['transaction_reference_id'] = $contract_no;
                $contractMove[$i]['qty']                      = '-' . $itemQty[$i];
                $contractMove[$i]['note']                     = $request->comments;
            }

            for ($i = 0; $i < count($contractDetail); $i++) {
                DB::table('job_contract_details')->where(['stock_id' => $contractDetail[$i]['stock_id'], 'job_contract_no' => $contract_no])->update($contractDetail[$i]);
                DB::table('job_contract_moves')->where(['stock_id' => $contractMove[$i]['stock_id'], 'reference' => 'store_out_' . $contract_no])->update($contractMove[$i]);
            }

        }

        \Session::flash('success', trans('message.success.save_success'));
        return redirect()->intended('contract/invoice/view-detail-invoice/' . $contract_ref_no . '/' . $contract_no);
    }

}
