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

        if (isset($_GET['product'])) {
            $data['product'] = $item = $_GET['product'];
        } else {
            $data['product'] = $item = '';
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

        for ($i = 0; $i < $arrayCount; $i++) {

            // create job contract details
            $jobContractDetail[$i]['job_contract_no']  = $jobContractId;
            $jobContractDetail[$i]['description']      = $description[$i];
            $jobContractDetail[$i]['qty_sent']         = 0;
            $jobContractDetail[$i]['quantity']         = $itemQuantity[$i];
            $jobContractDetail[$i]['trans_type']       = SALESORDER;
            $jobContractDetail[$i]['discount_percent'] = $itemDiscount[$i];
            $jobContractDetail[$i]['tax_type_id']      = $taxIds[$i];
            $jobContractDetail[$i]['unit_price']       = $unitPrice[$i];

        }

        for ($i = 0; $i < count($jobContractDetail); $i++) {
            //Fetch the maximum id from the job contract detail table
            $jobContractDetailMaxId            = DB::table('job_contract_details')->select('id')->orderBy('id', 'DESC')->first();
            $jobContractDetail[$i]['stock_id'] = $jobContractDetailMaxId->id;

            \DB::table('job_contract_details')->insertGetId($jobContractDetail[$i]);
        }

        if (!empty($jobContractId)) {
            \Session::flash('success', trans('message.success.save_success'));
            return redirect()->intended('contract/view-contract-details/' . $jobContractId);
        }
    }

    public function edit($contractNo)
    {
        $data['menu']         = 'contracts';
        $data['sub_menu']     = 'contract/list';
        $data['taxType']      = $this->sale->calculateTaxRow($contractNo, true);
        $data['customerData'] = DB::table('debtors_master')->get();
        $data['locData']      = DB::table('location')->get();
        $data['invoiceData']  = $this->jobContract->getJobContractByID($contractNo);
        $data['contractData'] = DB::table('job_contracts')->where('job_contract_no', '=', $contractNo)->first();
        $data['branchs']      = DB::table('cust_branch')->select('debtor_no', 'branch_code', 'br_name')->where('debtor_no', $data['contractData']->debtor_no)->orderBy('br_name', 'ASC')->get();
        $data['payments']     = DB::table('payment_terms')->get();
        $data['salesType']    = DB::table('sales_types')->select('sales_type', 'id')->get();

        //d($data['invoiceData'],1);

        $taxTypeList = DB::table('item_tax_types')->get();
        $taxOptions  = '';
        $selectStart = "<select class='form-control taxList' name='tax_id_new[]'>";
        $selectEnd   = "</select>";

        foreach ($taxTypeList as $key => $value) {
            $taxOptions .= "<option value='" . $value->id . "' taxrate='" . $value->tax_rate . "'>" . $value->name . '(' . $value->tax_rate . ')' . "</option>";
        }
        $data['tax_type_new'] = $taxOptions;
        $data['tax_types']    = $taxTypeList;

        return view('admin.jobContract.contractEdit', $data);
    }

    public function update(Request $request)
    {
        $userId          = \Auth::user()->id;
        $job_contract_no = $request->job_contract_no;
        $this->validate($request, [
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
        $jobContract['trans_type']    = SALESORDER;
        $jobContract['branch_id']     = $request->branch_id;
        $jobContract['payment_id']    = $request->payment_id;

        $jobContract['from_stk_loc']     = $request->from_stk_loc;
        $jobContract['comments']         = $request->comments;
        $jobContract['total']            = $request->total;
        $jobContract['discount_type']    = $request->discount_type;
        $jobContract['discount_percent'] = $request->perOrderDiscount;
        $jobContract['updated_at']       = date('Y-m-d H:i:s');
        //d($salesOrder,1);

        DB::table('job_contracts')->where('job_contract_no', $job_contract_no)->update($jobContract);


        if (count($itemQty) > 0) {
            //Delete the current records from the job contract details order
            DB::table('job_contract_details')->where('job_contract_no', '=', $job_contract_no)->delete();

            //Insert new rocords into the table
            for ($i = 0; $i < $arrayCount; $i++) {

                // create job contract details
                $jobContractDetail[$i]['job_contract_no']  = $job_contract_no;
                $jobContractDetail[$i]['description']      = $description[$i];
                $jobContractDetail[$i]['qty_sent']         = 0;
                $jobContractDetail[$i]['quantity']         = $itemQty[$i];
                $jobContractDetail[$i]['trans_type']       = SALESORDER;
                $jobContractDetail[$i]['discount_percent'] = $itemDiscount[$i];
                $jobContractDetail[$i]['tax_type_id']      = $taxIds[$i];
                $jobContractDetail[$i]['unit_price']       = $unitPrice[$i];

            }

            for ($i = 0; $i < count($jobContractDetail); $i++) {

                //Fetch the maximum id from the job contract detail table
                $jobContractDetailMaxId            = DB::table('job_contract_details')->select('id')->orderBy('id', 'DESC')->first();
                $jobContractDetail[$i]['stock_id'] = $jobContractDetailMaxId->id;

                \DB::table('job_contract_details')->insertGetId($jobContractDetail[$i]);
            }

        }

        \Session::flash('success', trans('message.success.save_success'));
        return redirect()->intended('contract/view-contract-details/' . $job_contract_no);
    }

    public function destroy($id)
    {
        if (isset($id)) {
            $record = \DB::table('job_contracts')->where('job_contract_no', $id)->first();
            if ($record) {

                // Delete Payment information
                DB::table('job_contract_payment_history')->where('contract_reference', '=', $record->reference)->delete();

                // Delete invoice information
                $invoice = \DB::table('job_contracts')->where('contract_reference_id', $record->job_contract_no)->first();

                DB::table('job_contracts')->where('contract_reference_id', '=', $record->job_contract_no)->delete();

                if (!empty($invoice)) {
                    DB::table('job_contract_details')->where('job_contract_no', '=', $invoice->job_contract_no)->delete();
                }
                // Delete order information
                DB::table('job_contracts')->where('job_contract_no', '=', $record->job_contract_no)->delete();
                DB::table('job_contract_details')->where('job_contract_no', '=', $record->job_contract_no)->delete();

                // Delete Stock information
                DB::table('job_contract_moves')->where('contract_no', '=', $record->job_contract_no)->delete();

                \Session::flash('success', trans('message.success.delete_success'));
                return redirect()->intended('contract/list');
            }
        }
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

        $data['contractData'] = DB::table('job_contracts')
            ->where('job_contract_no', '=', $contractNo)
            ->leftJoin('location', 'location.loc_code', '=', 'job_contracts.from_stk_loc')
            ->select("job_contracts.*", "location.location_name")
            ->first();

        $data['invoiceData'] = $this->jobContract->getJobContractByID($contractNo, $data['contractData']->from_stk_loc);

        $data['branchs'] = DB::table('cust_branch')->select('debtor_no', 'branch_code', 'br_name')->where('debtor_no', $data['contractData']->debtor_no)->orderBy('br_name', 'ASC')->get();

        $data['payments']      = DB::table('payment_terms')->get();
        $data['invoice_count'] = DB::table('job_contracts')->where('trans_type', SALESINVOICE)->count();

        $data['customerInfo'] = DB::table('job_contracts')
            ->where('job_contracts.job_contract_no', $contractNo)
            ->leftjoin('debtors_master', 'debtors_master.debtor_no', '=', 'job_contracts.debtor_no')
            ->leftjoin('cust_branch', 'cust_branch.branch_code', '=', 'job_contracts.branch_id')
            ->leftjoin('countries', 'countries.id', '=', 'cust_branch.shipping_country_id')
            ->select('debtors_master.debtor_no', 'debtors_master.name', 'debtors_master.phone', 'debtors_master.email', 'cust_branch.br_name', 'cust_branch.br_address', 'cust_branch.billing_street', 'cust_branch.billing_city', 'cust_branch.billing_state', 'cust_branch.billing_zip_code',
                'cust_branch.billing_country_id', 'cust_branch.shipping_street', 'cust_branch.shipping_city', 'cust_branch.shipping_state', 'cust_branch.shipping_zip_code', 'cust_branch.shipping_country_id', 'countries.country')
            ->first();

        $data['customer_branch']  = DB::table('cust_branch')->where('branch_code', $data['contractData']->branch_id)->first();
        $data['customer_payment'] = DB::table('payment_terms')->where('id', $data['contractData']->payment_id)->first();

        $data['invoiceList'] = DB::table('job_contracts')
            ->where('contract_reference', $data['contractData']->reference)
            ->select('job_contract_no', 'reference', 'contract_reference', 'total', 'paid_amount')
            ->orderBy('created_at', 'DESC')
            ->get();

        $data['invoiceQty'] = DB::table('job_contract_moves')->where(['contract_no' => $contractNo, 'trans_type' => SALESINVOICE])->sum('qty');
        /*$fetchInvoiveQty = DB::select(DB::raw("SELECT COALESCE(SUM(jcd.quantity), 0) AS total FROM job_contracts as jc LEFT JOIN job_contract_details as jcd ON jc.job_contract_no=jcd.job_contract_no WHERE jc.job_contract_no='$contractNo' AND jc.trans_type=" . SALESINVOICE));

        $data['invoiceQty']   = $fetchInvoiveQty[0]->total;*/
        $data['contractQty']  = DB::table('job_contract_details')->where(['job_contract_no' => $contractNo, 'trans_type' => SALESORDER])->sum('quantity');
        $data['contractInfo'] = DB::table('job_contracts')->where('job_contract_no', $contractNo)->select('reference', 'job_contract_no')->first();
        $data['paymentsList'] = DB::table('job_contract_payment_history')
            ->where(['contract_reference' => $data['contractInfo']->reference])
            ->leftjoin('payment_terms', 'payment_terms.id', '=', 'job_contract_payment_history.payment_type_id')
            ->select('job_contract_payment_history.*', 'payment_terms.name')
            ->orderBy('payment_date', 'DESC')
            ->get();
        $lang                 = Session::get('dflt_lang');
        $data['emailInfo']    = DB::table('email_temp_details')->where(['temp_id' => 5, 'lang' => $lang])->select('subject', 'body')->first();


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


    public function contractPrint($contractNo)
    {
        $data['taxInfo']      = $this->sale->calculateTaxRow($contractNo, true);
        $data['contractData'] = DB::table('job_contracts')
            ->where('job_contract_no', '=', $contractNo)
            ->leftJoin('location', 'location.loc_code', '=', 'job_contracts.from_stk_loc')
            ->select("job_contracts.*", "location.location_name")
            ->first();
        $data['invoiceData']  = $this->jobContract->getJobContractByID($contractNo, $data['contractData']->from_stk_loc);
        $data['customerInfo'] = DB::table('job_contracts')
            ->where('job_contracts.job_contract_no', $contractNo)
            ->leftjoin('debtors_master', 'debtors_master.debtor_no', '=', 'job_contracts.debtor_no')
            ->leftjoin('cust_branch', 'cust_branch.branch_code', '=', 'job_contracts.branch_id')
            ->leftjoin('countries', 'countries.id', '=', 'cust_branch.shipping_country_id')
            ->select('debtors_master.debtor_no', 'debtors_master.name', 'debtors_master.phone', 'debtors_master.email', 'cust_branch.br_name', 'cust_branch.br_address', 'cust_branch.billing_street', 'cust_branch.billing_city', 'cust_branch.billing_state', 'cust_branch.billing_zip_code',
                'cust_branch.billing_country_id', 'cust_branch.shipping_street', 'cust_branch.shipping_city', 'cust_branch.shipping_state', 'cust_branch.shipping_zip_code', 'cust_branch.shipping_country_id', 'countries.country')
            ->first();
        // return view('admin.salesOrder.orderPdf', $data);
        $pdf = PDF::loadView('admin.jobContract.contractPrint', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('contract_' . time() . '.pdf', array("Attachment" => 0));

        return view('admin.jobContract.contractPrint', $data);
    }


    public function contractPdf($contractNo)
    {
        $data['taxInfo']      = $this->sale->calculateTaxRow($contractNo, true);
        $data['contractData'] = DB::table('job_contracts')
            ->where('job_contract_no', '=', $contractNo)
            ->leftJoin('location', 'location.loc_code', '=', 'job_contracts.from_stk_loc')
            ->select("job_contracts.*", "location.location_name")
            ->first();
        $data['invoiceData']  = $this->jobContract->getJobContractByID($contractNo, $data['contractData']->from_stk_loc);
        $data['customerInfo'] = DB::table('job_contracts')
            ->where('job_contracts.job_contract_no', $contractNo)
            ->leftjoin('debtors_master', 'debtors_master.debtor_no', '=', 'job_contracts.debtor_no')
            ->leftjoin('cust_branch', 'cust_branch.branch_code', '=', 'job_contracts.branch_id')
            ->leftjoin('countries', 'countries.id', '=', 'cust_branch.shipping_country_id')
            ->select('debtors_master.debtor_no', 'debtors_master.name', 'debtors_master.phone', 'debtors_master.email', 'cust_branch.br_name', 'cust_branch.br_address', 'cust_branch.billing_street', 'cust_branch.billing_city', 'cust_branch.billing_state', 'cust_branch.billing_zip_code',
                'cust_branch.billing_country_id', 'cust_branch.shipping_street', 'cust_branch.shipping_city', 'cust_branch.shipping_state', 'cust_branch.shipping_zip_code', 'cust_branch.shipping_country_id', 'countries.country')
            ->first();
        // return view('admin.salesOrder.orderPdf', $data);
        $pdf = PDF::loadView('admin.salesOrder.orderPdf', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('contract_' . time() . '.pdf', array("Attachment" => 0));
    }


    public function quantityValidationWithLocaltion(Request $request)
    {
        $location          = $request['location'];
        $items             = $request['itemInfo'];
        $data['status_no'] = 0;
        $data['item']      = trans('message.invoice.item_insufficient_message');
        //d($items,1);
        foreach ($items as $result) {
            $qty = DB::table('job_contract_moves')
                ->select(DB::raw('sum(qty) as total'))
                ->where(['stock_id' => $result['stockid'], 'loc_code' => $location])
                ->groupBy('loc_code')
                ->first();
            if (empty($qty)) {
                return json_encode($data);
            } else {
                if ($qty < $result['qty']) {
                    return json_encode($data);
                } else {
                    $datas['status_no'] = 1;
                    return json_encode($datas);
                }
            }
        }
    }


    public function quantityValidationEditInvoice(Request $request)
    {
        $location         = $request['location_id'];
        $item_id          = $request['item_id'];
        $stock_id         = $request['stock_id'];
        $set_qty          = $request['qty'];
        $invoice_order_no = $request['invoice_no'];
        $order_reference  = $request['order_reference'];
        $order            = DB::table('job_contracts')->where('reference', $request['order_reference'])->select('job_contract_no')->first();
        $orderItemQty     = DB::table('job_contract_details')
            ->where(['job_contract_no' => $order->job_contract_no, 'stock_id' => $stock_id])
            ->select('quantity')
            ->first();

        $salesItemQty = DB::table('job_contract_moves')
            ->where(['contract_reference' => $order_reference, 'stock_id' => $stock_id, 'loc_code' => $location])
            ->where('reference', '!=', 'store_out_' . $invoice_order_no)
            ->sum('qty');

        $itemAvailable = $orderItemQty->quantity + ($salesItemQty);

        if ($set_qty > $itemAvailable) {
            $data['status_no'] = 0;
            $data['qty']       = "qty Insufficient";
        } else {
            $data['status_no'] = 1;
            $data['qty']       = "qty available";
        }
        return json_encode($data);
    }


    public function manualInvoiceCreate($contractNo)
    {
        $data['menu']     = 'contracts';
        $data['sub_menu'] = 'contract/direct-invoice';

        $data['taxType']      = $this->jobContract->calculateTaxRowRestItem($contractNo);
        $data['customerData'] = DB::table('debtors_master')->get();
        $data['locData']      = DB::table('location')->get();
        $data['invoiceData']  = $this->jobContract->getRestOrderItemsByOrderID($contractNo);
        $data['contractData'] = DB::table('job_contracts')->where('job_contract_no', '=', $contractNo)->first();
        $data['branchs']      = DB::table('cust_branch')->select('debtor_no', 'branch_code', 'br_name')->where('debtor_no', $data['contractData']->debtor_no)->orderBy('br_name', 'ASC')->get();
        $data['payments']     = DB::table('payment_terms')->get();
        $invoice_count        = DB::table('job_contracts')->where('trans_type', SALESINVOICE)->count();

        $data['contract_no']   = $contractNo;
        $data['invoiceedItem'] = $this->jobContract->getInvoicedItemsQty($contractNo);
        $data['paymentTerms']  = DB::table('invoice_payment_terms')->get();

        if ($invoice_count > 0) {
            $invoiceReference = DB::table('job_contracts')->where('trans_type', SALESINVOICE)->select('reference')->orderBy('job_contract_no', 'DESC')->first();

            $ref                   = explode("-", $invoiceReference->reference);
            $data['invoice_count'] = (int)$ref[1];
        } else {
            $data['invoice_count'] = 0;
        }

        return view('admin.jobContract.createManualInvoice', $data);
    }


    /**
     * Store manaul invoice
     */
    public function storeManualInvoice(Request $request)
    {
        // d($request->all(),1);
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
        $stock_id    = $request->stock_id;
        $description  = $request->description;
        $arrayCount   = $request->arrayCount;

        // Create salesOrder Invoice start
        $jobContractInvoice['contract_reference_id'] = $request->job_contract_no;
        $jobContractInvoice['contract_reference']    = $request->contract_reference;
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
        $jobContractInvoice['discount_type']         = $request->discount_type;
        $jobContractInvoice['discount_percent']      = $request->perOrderDiscount;
        $jobContractInvoice['payment_term']          = $request->payment_term;
        $jobContractInvoice['created_at']            = date('Y-m-d H:i:s');

        $contractInvoiceId = DB::table('job_contracts')->insertGetId($jobContractInvoice);

        // Create contract Invoice end

        for ($i = 0; $i < $arrayCount; $i++) {

            // Create contractDetailInvoice Start
            $contractDetailInvoice[$i]['job_contract_no']  = $contractInvoiceId;
            $contractDetailInvoice[$i]['stock_id']         = $stock_id[$i];
            $contractDetailInvoice[$i]['description']      = $description[$i];
            $contractDetailInvoice[$i]['qty_sent']         = $itemQuantity[$i];
            $contractDetailInvoice[$i]['quantity']         = $itemQuantity[$i];
            $contractDetailInvoice[$i]['trans_type']       = SALESINVOICE;
            $contractDetailInvoice[$i]['discount_percent'] = $itemDiscount[$i];
            $contractDetailInvoice[$i]['tax_type_id']      = $taxIds[$i];
            $contractDetailInvoice[$i]['unit_price']       = $unitPrice[$i];
            // Create contractDetailInvoice End

            // create jobContractMove
            $contractMove[$i]['stock_id']                 = $stock_id[$i];
            $contractMove[$i]['contract_no']              = $request->job_contract_no;
            $contractMove[$i]['loc_code']                 = $request->from_stk_loc;
            $contractMove[$i]['tran_date']                = DbDateFormat($request->contract_date);
            $contractMove[$i]['person_id']                = $userId;
            $contractMove[$i]['reference']                = 'store_out_' . $contractInvoiceId;
            $contractMove[$i]['transaction_reference_id'] = $contractInvoiceId;
            $contractMove[$i]['qty']                      = '-' . $itemQuantity[$i];
            $contractMove[$i]['price']                    = $unitPrice[$i];
            $contractMove[$i]['trans_type']               = SALESINVOICE;
            $contractMove[$i]['contract_reference']       = $request->contract_reference;


        }

        for ($i = 0; $i < count($contractDetailInvoice); $i++) {
            DB::table('job_contract_details')->insertGetId($contractDetailInvoice[$i]);
            DB::table('job_contract_moves')->insertGetId($contractMove[$i]);
        }

        if (!empty($contractInvoiceId)) {
            \Session::flash('success', trans('message.success.save_success'));
            return redirect()->intended('contract/invoice/view-detail-invoice/' . $request->job_contract_no . '/' . $contractInvoiceId);
        }
    }


    public function autoInvoiceCreate($contractNo)
    {
        $userId       = \Auth::user()->id;
        $invoiceCount = DB::table('job_contracts')->where('trans_type', SALESINVOICE)->count();
        if ($invoiceCount > 0) {
            $invoiceReference = DB::table('job_contracts')->where('trans_type', SALESINVOICE)->select('reference')->orderBy('job_contract_no', 'DESC')->first();

            $ref           = explode("-", $invoiceReference->reference);
            $invoice_count = (int)$ref[1];
        } else {
            $invoice_count = 0;
        }

        $invoiceInfos = $this->jobContract->getRestOrderItemsByOrderID($contractNo);
        $contractInfo = DB::table('job_contracts')->where('job_contract_no', '=', $contractNo)->first();

        // Check quantity is available or not on location
        /*foreach ($invoiceInfos as $key => $res) {
            $availableQty = getItemQtyByLocationNameForContract($res->location, $res->stock_id);
            if ($availableQty < $res->quantity) {
                return redirect()->intended('contract/manual-invoice-create/' . $contractNo)->withErrors(['email' => "Item quantity not enough for this invoice !"]);
            }
        }*/

        $payment_term      = DB::table('invoice_payment_terms')->where('defaults', 1)->select('id')->first();
        $total             = 0;
        $price             = 0;
        $discountAmount    = 0;
        $priceWithDiscount = 0;
        $taxAmount         = 0;
        $priceWithTax      = 0;
        foreach ($invoiceInfos as $key => $info) {
            $price             = ($info->unit_price * $info->item_rest);
            $discountAmount    = (($price * $info->discount_percent) / 100);
            $priceWithDiscount = ($price - $discountAmount);
            $taxAmount         = (($priceWithDiscount * $info->tax_rate) / 100);
            $priceWithTax      = ($priceWithDiscount + $taxAmount);
            $total += $priceWithTax;


        }

        // Create jobContract Invoice start
        $jobContractInvoice['contract_reference_id'] = $contractNo;
        $jobContractInvoice['contract_reference']    = $contractInfo->reference;
        $jobContractInvoice['trans_type']            = SALESINVOICE;
        $jobContractInvoice['reference']             = 'INV-' . sprintf("%04d", $invoice_count + 1);
        $jobContractInvoice['debtor_no']             = $contractInfo->debtor_no;
        $jobContractInvoice['branch_id']             = $contractInfo->branch_id;
        $jobContractInvoice['person_id']             = $userId;
        $jobContractInvoice['payment_id']            = $contractInfo->payment_id;
        $jobContractInvoice['comments']              = $contractInfo->comments;
        $jobContractInvoice['contract_date']         = $contractInfo->contract_date;
        $jobContractInvoice['from_stk_loc']          = $contractInfo->from_stk_loc;
        $jobContractInvoice['total']                 = $total;
        $jobContractInvoice['discount_type']         = $contractInfo->discount_type;
        $jobContractInvoice['discount_percent']      = $contractInfo->discount_percent;
        $jobContractInvoice['payment_term']          = $payment_term->id;
        $jobContractInvoice['created_at']            = date('Y-m-d H:i:s');

        $contractInvoiceId = DB::table('job_contracts')->insertGetId($jobContractInvoice);

        foreach ($invoiceInfos as $i => $invoiceInfo) {
            if ($invoiceInfo->item_rest > 0) {
                $contractDetailInvoice['job_contract_no']  = $contractInvoiceId;
                $contractDetailInvoice['stock_id']         = $invoiceInfo->stock_id;
                $contractDetailInvoice['description']      = $invoiceInfo->description;
                $contractDetailInvoice['qty_sent']         = $invoiceInfo->item_rest;
                $contractDetailInvoice['quantity']         = $invoiceInfo->item_rest;
                $contractDetailInvoice['trans_type']       = SALESINVOICE;
                $contractDetailInvoice['discount_percent'] = $invoiceInfo->discount_percent;
                $contractDetailInvoice['tax_type_id']      = $invoiceInfo->tax_type_id;
                $contractDetailInvoice['unit_price']       = $invoiceInfo->unit_price;
                // Create jobContractDetailInvoice End

                // create jobContractMove
                $contractMove['stock_id']                 = $invoiceInfo->stock_id;
                $contractMove['contract_no']              = $contractNo;
                $contractMove['loc_code']                 = $contractInfo->from_stk_loc;
                $contractMove['tran_date']                = date('Y-m-d');
                $contractMove['person_id']                = $userId;
                $contractMove['reference']                = 'store_out_' . $contractInvoiceId;
                $contractMove['transaction_reference_id'] = $contractInvoiceId;
                $contractMove['qty']                      = '-' . $invoiceInfo->item_rest;
                $contractMove['price']                    = $invoiceInfo->unit_price;
                $contractMove['trans_type']               = SALESINVOICE;
                $contractMove['contract_reference']       = $contractInfo->reference;

                DB::table('job_contract_details')->insertGetId($contractDetailInvoice);
                DB::table('job_contract_moves')->insertGetId($contractMove);
            }
        }
        \Session::flash('success', trans('message.success.save_success'));
        return redirect()->intended('contract/invoice/view-detail-invoice/' . $contractNo . '/' . $contractInvoiceId);
    }


}
