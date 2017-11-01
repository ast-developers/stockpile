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

    public function store(Request $request)
    {
        $userId = \Auth::user()->id;
        $this->validate($request, [
            'reference'     => 'required|unique:job_contracts',
            'from_stk_loc'  => 'required',
            'contract_date'      => 'required',
            'debtor_no'     => 'required',
            'branch_id'     => 'required',
            'payment_id'    => 'required',
            'item_quantity' => 'required',
        ]);

//d(SALESORDER,1);
        $itemQuantity = $request->item_quantity;
        $itemIds      = $request->item_id;
        $itemDiscount = $request->discount;
        $taxIds       = $request->tax_id;
        $unitPrice    = $request->unit_price;
        $description  = $request->description;
        $stock_id     = $request->stock_id;

        foreach ($itemQuantity as $key => $itemQty) {
            $product[$itemIds[$key]] = $itemQty;
        }

        // create salesOrder
        $salesOrder['debtor_no']    = $request->debtor_no;
        $salesOrder['branch_id']    = $request->branch_id;
        $salesOrder['payment_id']   = $request->payment_id;
        $salesOrder['person_id']    = $userId;
        $salesOrder['reference']    = $request->reference;
        $salesOrder['comments']     = $request->comments;
        $salesOrder['trans_type']   = SALESORDER;
        $salesOrder['ord_date']     = DbDateFormat($request->ord_date);
        $salesOrder['from_stk_loc'] = $request->from_stk_loc;
        $salesOrder['total']        = $request->total;
        $salesOrder['delivery_price']        = $request->delivery_price;
        $salesOrder['discount_type']        = $request->discount_type;
        $salesOrder['discount_percent']        = $request->perOrderDiscount;
        $salesOrder['created_at']   = date('Y-m-d H:i:s');
        // d($salesOrder,1);
        $salesOrderId = \DB::table('sales_orders')->insertGetId($salesOrder);


        for ($i = 0; $i < count($itemIds); $i++) {
            foreach ($product as $key => $item) {

                if ($itemIds[$i] == $key) {
                    // create salesOrderDetail
                    $salesOrderDetail[$i]['order_no']         = $salesOrderId;
                    $salesOrderDetail[$i]['stock_id']         = $stock_id[$i];
                    $salesOrderDetail[$i]['description']      = $description[$i];
                    $salesOrderDetail[$i]['qty_sent']         = 0;
                    $salesOrderDetail[$i]['quantity']         = $item;
                    $salesOrderDetail[$i]['trans_type']       = SALESORDER;
                    $salesOrderDetail[$i]['discount_percent'] = $itemDiscount[$i];
                    $salesOrderDetail[$i]['tax_type_id']      = $taxIds[$i];
                    $salesOrderDetail[$i]['unit_price']       = $unitPrice[$i];
                }
            }
        }

        for ($i = 0; $i < count($salesOrderDetail); $i++) {
            \DB::table('sales_order_details')->insertGetId($salesOrderDetail[$i]);
        }

        if (!empty($salesOrderId)) {
            \Session::flash('success', trans('message.success.save_success'));
            return redirect()->intended('order/view-order-details/' . $salesOrderId);
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

}
