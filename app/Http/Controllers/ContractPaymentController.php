<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\ContractPayment;
use Validator;
use DB;
use Session;
use Auth;
use PDF;

class ContractPaymentController extends Controller
{
    public function __construct(Auth $auth, ContractPayment $contractPayment, EmailController $email)
    {
        $this->auth            = $auth::user();
        $this->contractPayment = $contractPayment;
        $this->email           = $email;
    }


    public function index()
    {
        $data['menu']        = 'contracts';
        $data['sub_menu']    = 'contract/payment/list';
        $data['paymentList'] = DB::table('job_contract_payment_history')
            ->leftjoin('debtors_master', 'debtors_master.debtor_no', '=', 'job_contract_payment_history.customer_id')
            ->leftjoin('payment_terms', 'payment_terms.id', '=', 'job_contract_payment_history.payment_type_id')
            ->leftjoin('job_contracts', 'job_contracts.reference', '=', 'job_contract_payment_history.invoice_reference')
            ->select('job_contract_payment_history.*', 'debtors_master.name', 'payment_terms.name as pay_type', 'job_contracts.job_contract_no as invoice_id', 'job_contracts.contract_reference_id as contract_id')
            ->orderBy('job_contract_payment_history.payment_date', 'DESC')
            ->get();
        return view('admin.jobContractPayment.paymentList', $data);
    }

    public function paymentFiltering()
    {
        $data['menu']     = 'contracts';
        $data['sub_menu'] = 'contract/payment/list';
        $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $data['method']   = $method = isset($_GET['method']) ? $_GET['method'] : null;

        $data['customerList'] = DB::table('debtors_master')->select('debtor_no', 'name')->where(['inactive' => 0])->get();
        $data['methodList']   = DB::table('payment_terms')->select('id', 'name')->get();

        $fromDate = DB::table('job_contract_payment_history')->select('payment_date')->orderBy('payment_date', 'asc')->first();

        if (isset($_GET['from'])) {
            $data['from'] = $from = $_GET['from'];
        } else {
            $data['from'] = $from = formatDate(date("d-m-Y", strtotime($fromDate->payment_date)));
        }

        if (isset($_GET['to'])) {
            $data['to'] = $to = $_GET['to'];
        } else {
            $data['to'] = $to = formatDate(date('d-m-Y'));
        }

        $data['paymentList'] = $this->contractPayment->paymentFilter($from, $to, $customer, $method);
        return view('admin.jobContractPayment.filterPaymentList', $data);
    }


    public function payAllAmount($contract_no, $editJobContract = '')
    {
        //Fetch data after generating invoice
        $allInvoiced = DB::table('job_contracts')->where('contract_reference_id', $contract_no)->select('job_contract_no as inv_no', 'contract_reference', 'reference', 'debtor_no as customer_id', 'payment_id', 'total as invoiced_amount', 'paid_amount')->get();


        //Fetch data without generating invoice
        if (empty($allInvoiced)) {
            $allInvoiced = DB::table('job_contracts')->where('job_contract_no', $contract_no)->select('job_contract_no as inv_no', 'contract_reference', 'reference', 'debtor_no as customer_id', 'payment_id', 'total as invoiced_amount', 'paid_amount')->get();
        }

        //d($allInvoiced,1);
        foreach ($allInvoiced as $key => $value) {
            $amount = ($value->invoiced_amount - $value->paid_amount);

            if (abs($amount) > 0) {
                $payment[$key]['invoice_reference']  = (string)$value->reference;
                $payment[$key]['contract_reference'] = ($value->contract_reference) ? (string)$value->contract_reference : (string)$value->reference;
                $payment[$key]['payment_type_id']    = $value->payment_id;
                $payment[$key]['amount']             = $amount;
                $payment[$key]['payment_date']       = DbDateFormat(date('d-m-Y'));
                $payment[$key]['reference']          = 'by all pay';
                $payment[$key]['person_id']          = $this->auth->id;
                $payment[$key]['customer_id']        = $value->customer_id;
                //d($payment,1);
                $payments = DB::table('job_contract_payment_history')->insertGetId($payment[$key]);
                DB::table('job_contracts')->where('job_contract_no', $value->inv_no)->update(['paid_amount' => $value->invoiced_amount]);
            }
        }


        \Session::flash('success', trans('message.extra_text.payment_success'));
        return redirect()->intended('contract/view-contract-details/' . $contract_no);
    }


    public function viewReceipt($id)
    {
        $data['menu']        = 'contracts';
        $data['sub_menu']    = 'contract/payment/list';
        $data['paymentInfo'] = DB::table('job_contract_payment_history')
            ->leftjoin('payment_terms', 'payment_terms.id', '=', 'job_contract_payment_history.payment_type_id')
            ->leftjoin('job_contracts', 'job_contracts.reference', '=', 'job_contract_payment_history.invoice_reference')
            ->leftjoin('debtors_master', 'debtors_master.debtor_no', '=', 'job_contract_payment_history.customer_id')
            ->leftjoin('cust_branch', 'cust_branch.branch_code', '=', 'job_contracts.branch_id')
            ->leftjoin('countries', 'countries.id', '=', 'cust_branch.billing_country_id')
            ->where('job_contract_payment_history.id', $id)
            ->select('job_contract_payment_history.*', 'payment_terms.name as payment_method', 'cust_branch.br_name', 'cust_branch.br_address', 'cust_branch.billing_street', 'cust_branch.billing_city', 'cust_branch.billing_state', 'cust_branch.billing_zip_code', 'cust_branch.billing_country_id',
                'job_contracts.contract_date as invoice_date', 'job_contracts.total as invoice_amount', 'job_contracts.contract_reference_id', 'job_contracts.job_contract_no', 'countries.country', 'debtors_master.email', 'debtors_master.phone', 'debtors_master.name')
            ->first();

        //Right part start
        $data['invoiceList'] = DB::table('job_contracts')
            ->where('contract_reference', $data['paymentInfo']->contract_reference)
            ->select('job_contract_no', 'reference', 'contract_reference', 'total', 'paid_amount')
            ->orderBy('created_at', 'DESC')
            ->get();

        $data['invoiceQty'] = DB::table('job_contract_moves')->where(['contract_no' => $data['paymentInfo']->contract_reference_id, 'trans_type' => SALESINVOICE])->sum('qty');
        if (empty($data['invoiceQty'])) {
            $data['invoiceQty'] = DB::table('job_contract_moves')->where(['job_contract_no' => $data['paymentInfo']->job_contract_no, 'trans_type' => SALESINVOICE])->sum('qty');
        }

        $data['contractQty'] = DB::table('job_contract_details')->where(['job_contract_no' => $data['paymentInfo']->contract_reference_id, 'trans_type' => SALESORDER])->sum('quantity');
        if (empty($data['contractQty'])) {
            $data['contractQty'] = DB::table('job_contract_details')->where(['job_contract_no' => $data['paymentInfo']->job_contract_no, 'trans_type' => SALESORDER])->sum('quantity');
        }

        $data['paymentsList'] = DB::table('job_contract_payment_history')
            ->where(['contract_reference' => $data['paymentInfo']->contract_reference])
            ->leftjoin('payment_terms', 'payment_terms.id', '=', 'job_contract_payment_history.payment_type_id')
            ->select('job_contract_payment_history.*', 'payment_terms.name')
            ->orderBy('payment_date', 'DESC')
            ->get();


        //----If shipment and payment done without invoice----
        if ($data['paymentInfo']->contract_reference_id == 0) {
            $data['contractInfo'] = DB::table('job_contracts')->where('job_contract_no', $data['paymentInfo']->job_contract_no)->select('reference', 'job_contract_no')->first();
        } // If shipment and payment done after invoice generation
        else {
            $data['contractInfo'] = DB::table('job_contracts')->where('job_contract_no', $data['paymentInfo']->contract_reference_id)->select('reference', 'job_contract_no')->first();
        }

        //Right part end
        $lang              = Session::get('dflt_lang');
        $data['emailInfo'] = DB::table('email_temp_details')->where(['temp_id' => 1, 'lang' => $lang])->select('subject', 'body')->first();

        return view('admin.jobContractPayment.viewReceipt', $data);
    }

    public function printReceipt($id)
    {
        $data['paymentInfo'] = DB::table('job_contract_payment_history')
            ->leftjoin('payment_terms', 'payment_terms.id', '=', 'job_contract_payment_history.payment_type_id')
            ->leftjoin('job_contracts', 'job_contracts.reference', '=', 'job_contract_payment_history.invoice_reference')
            ->leftjoin('debtors_master', 'debtors_master.debtor_no', '=', 'job_contract_payment_history.customer_id')
            ->leftjoin('cust_branch', 'cust_branch.branch_code', '=', 'job_contracts.branch_id')
            ->leftjoin('countries', 'countries.id', '=', 'cust_branch.billing_country_id')
            ->where('job_contract_payment_history.id', $id)
            ->select('job_contract_payment_history.*', 'payment_terms.name as payment_method', 'cust_branch.br_name', 'cust_branch.br_address', 'cust_branch.billing_street', 'cust_branch.billing_city', 'cust_branch.billing_state', 'cust_branch.billing_zip_code', 'cust_branch.billing_country_id',
                'job_contracts.contract_date as invoice_date', 'job_contracts.total as invoice_amount', 'job_contracts.contract_reference_id', 'countries.country', 'debtors_master.email', 'debtors_master.phone', 'debtors_master.name')
            ->first();

        //return view('admin.payment.printReceipt', $data);
        $pdf = PDF::loadView('admin.jobContractPayment.printReceipt', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('payment_' . time() . '.pdf', array("Attachment" => 0));
    }


    public function createReceiptPdf($id)
    {
        $data['paymentInfo'] = DB::table('job_contract_payment_history')
            ->leftjoin('payment_terms', 'payment_terms.id', '=', 'job_contract_payment_history.payment_type_id')
            ->leftjoin('job_contracts', 'job_contracts.reference', '=', 'job_contract_payment_history.invoice_reference')
            ->leftjoin('debtors_master', 'debtors_master.debtor_no', '=', 'job_contract_payment_history.customer_id')
            ->leftjoin('cust_branch', 'cust_branch.branch_code', '=', 'job_contracts.branch_id')
            ->leftjoin('countries', 'countries.id', '=', 'cust_branch.billing_country_id')
            ->where('job_contract_payment_history.id', $id)
            ->select('job_contract_payment_history.*', 'payment_terms.name as payment_method', 'cust_branch.br_name', 'cust_branch.br_address', 'cust_branch.billing_street', 'cust_branch.billing_city', 'cust_branch.billing_state', 'cust_branch.billing_zip_code', 'cust_branch.billing_country_id',
                'job_contracts.contract_date as invoice_date', 'job_contracts.total as invoice_amount', 'job_contracts.contract_reference_id', 'countries.country', 'debtors_master.email', 'debtors_master.phone', 'debtors_master.name')
            ->first();

        $pdf = PDF::loadView('admin.jobContractPayment.paymentReceiptPdf', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('payment_' . time() . '.pdf', array("Attachment" => 0));
    }

    public function delete(Request $request)
    {
        $id              = $request['id'];
        $paymentInfo     = DB::table('job_contract_payment_history')
            ->where('id', $id)
            ->select('id', 'contract_reference', 'invoice_reference', 'amount')
            ->first();
        $totalPaidAmount = DB::table('job_contracts')
            ->where(['contract_reference' => $paymentInfo->contract_reference, 'reference' => $paymentInfo->invoice_reference])
            ->sum('paid_amount');
        $newAmount       = ($totalPaidAmount - $paymentInfo->amount);
        $update          = DB::table('job_contracts')
            ->where(['contract_reference' => $paymentInfo->contract_reference, 'reference' => $paymentInfo->invoice_reference])
            ->update(['paid_amount' => $newAmount]);

        DB::table('job_contract_payment_history')->where('id', $id)->delete();
        \Session::flash('success', trans('message.success.save_success'));
        return redirect()->intended('contract/payment/list');
    }

    public function createPayment(Request $request)
    {
        $this->validate($request, [
            'amount'          => 'required|numeric',
            'payment_type_id' => 'required',
            'payment_date'    => 'required'
        ]);

        $payment['invoice_reference']  = $request->invoice_reference;
        $payment['contract_reference'] = $request->contract_reference;
        $payment['payment_type_id']    = $request->payment_type_id;
        $payment['amount']             = $request->amount;
        $payment['payment_date']       = DbDateFormat($request->payment_date);
        $payment['reference']          = $request->reference;
        $payment['person_id']          = $this->auth->id;
        $payment['customer_id']        = $request->customer_id;

        $contractNo   = $request->contract_no;
        $invoiceNo = $request->invoice_no;
        $payment   = DB::table('job_contract_payment_history')->insertGetId($payment);

        if (!empty($payment)) {
            $paidAmount = $this->contractPayment->updatePayment($request->invoice_reference, $request->amount);
            \Session::flash('success', trans('message.success.save_success'));
            return redirect()->intended('contract/invoice/view-detail-invoice/' . $contractNo . '/' . $invoiceNo);
        }
    }


}
