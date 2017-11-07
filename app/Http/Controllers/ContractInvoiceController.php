<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EmailController;
use Illuminate\Http\Request;
use App\Model\Sales;
use App\Model\JobContract;
use DB;
use PDF;
use Session;

use App\Http\Requests;

class ContractInvoiceController extends Controller
{
    public function __construct(JobContract $jobContract, Sales $sales, EmailController $email)
    {
        $this->jobContract = $jobContract;
        $this->sale = $sales;
        $this->email = $email;
    }


    /**
     * Preview of Invoice details
     * @params order_no, invoice_no
     **/

    public function viewInvoiceDetails($contractNo,$invoiceNo){
        $data['menu'] = 'contracts';
        $data['sub_menu'] = 'contract/direct-invoice';

        //$data['taxType'] = $this->sale->calculateTaxRow($invoiceNo);
        $data['taxType'] = $this->sale->calculateTaxRow($invoiceNo, true);
        if(empty($data['taxType'])){
            if(empty($data['taxType'] = $this->sale->calculateTaxRow($contractNo, true)));
        }

        $data['locData'] = DB::table('location')->get();

        $data['jobContractData'] = DB::table('job_contracts')
            ->where('job_contract_no', '=', $contractNo)
            ->leftJoin('location','location.loc_code','=','job_contracts.from_stk_loc')
            ->select("job_contracts.*","location.location_name")
            ->first();

        $data['contractDataInvoice'] = DB::table('job_contracts')
            ->where('job_contract_no', '=', $invoiceNo)
            ->leftJoin('location','location.loc_code','=','job_contracts.from_stk_loc')
            ->leftJoin('invoice_payment_terms','invoice_payment_terms.id','=','job_contracts.payment_term')
            ->select("job_contracts.*","location.location_name",'invoice_payment_terms.days_before_due')
            ->first();

        $data['invoiceData'] = $this->jobContract->getJobContractByID($invoiceNo,$data['contractDataInvoice']->from_stk_loc);

        if(empty($data['invoiceData'])){
            $data['invoiceData'] = $this->jobContract->getJobContractByID($contractNo,$data['contractDataInvoice']->from_stk_loc);
        }

        //d($data['saleDataOrder']);
        //d($data['saleDataInvoice'],1);

        $data['invoice_count'] = DB::table('job_contracts')->where('trans_type',SALESINVOICE)->count();

        $data['customerInfo']  = DB::table('job_contracts')
            ->where('job_contracts.job_contract_no',$contractNo)
            ->leftjoin('debtors_master','debtors_master.debtor_no','=','job_contracts.debtor_no')
            ->leftjoin('cust_branch','cust_branch.branch_code','=','job_contracts.branch_id')
            ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
            ->select('debtors_master.debtor_no','debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','countries.country','cust_branch.billing_country_id')
            ->first();
        //d($data['customerInfo'],1);

        $data['customer_branch'] = DB::table('cust_branch')->where('branch_code',$data['jobContractData']->branch_id)->first();
        $data['customer_payment'] = DB::table('payment_terms')->where('id',$data['jobContractData']->payment_id)->first();

        $data['invoiceList'] = DB::table('job_contracts')
            ->where('contract_reference',$data['jobContractData']->reference)
            ->select('job_contract_no','reference','contract_reference','total','paid_amount')
            ->orderBy('created_at','DESC')
            ->get();

        $data['contractInfo']  = DB::table('job_contracts')->where('job_contract_no',$contractNo)->select('reference','job_contract_no')->first();

        $data['invoiceQty'] = DB::table('job_contract_moves')->where(['contract_no'=>$contractNo,'trans_type'=>SALESINVOICE])->sum('qty');
        $data['contractQty']   = DB::table('job_contract_details')->where(['job_contract_no'=>$contractNo,'trans_type'=>SALESORDER])->sum('quantity');
        $data['payments']   = DB::table('payment_terms')->get();
        $data['paymentsList'] = DB::table('job_contract_payment_history')
            ->where(['contract_reference'=>$data['contractInfo']->reference])
            ->leftjoin('payment_terms','payment_terms.id','=','job_contract_payment_history.payment_type_id')
            ->select('job_contract_payment_history.*','payment_terms.name')
            ->orderBy('payment_date','DESC')
            ->get();
        $data['invoice_no'] = $invoiceNo;
        $lang = Session::get('dflt_lang');
        $data['emailInfo'] = DB::table('email_temp_details')->where(['temp_id'=>4,'lang'=>$lang])->select('subject','body')->first();
        $data['due_date']  = formatDate(date('Y-m-d', strtotime("+".$data['contractDataInvoice']->days_before_due."days")));

        return view('admin.jobContractInvoice.viewInvoiceDetails', $data);
    }


    public function invoicePrint($contractNo,$invoiceNo)
    {
        $data['taxInfo'] = $this->sale->calculateTaxRow($invoiceNo, true);
        $data['contractDataInvoice'] = DB::table('job_contracts')
            ->where('job_contract_no', '=', $invoiceNo)
            ->leftJoin('location','location.loc_code','=','job_contracts.from_stk_loc')
            ->leftJoin('invoice_payment_terms','invoice_payment_terms.id','=','job_contracts.payment_term')
            ->select("job_contracts.*","location.location_name",'invoice_payment_terms.days_before_due')
            ->first();
        // d($data['saleDataInvoice'],1);
        $data['invoiceData'] = $this->jobContract->getJobContractByID($invoiceNo,$data['contractDataInvoice']->from_stk_loc);

        $data['customerInfo']  = DB::table('job_contracts')
            ->where('job_contracts.job_contract_no',$contractNo)
            ->leftjoin('debtors_master','debtors_master.debtor_no','=','job_contracts.debtor_no')
            ->leftjoin('cust_branch','cust_branch.branch_code','=','job_contracts.branch_id')
            ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
            ->select('debtors_master.debtor_no','debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','countries.country','cust_branch.billing_country_id')
            ->first();
        $data['contractInfo']  = DB::table('job_contracts')->where('job_contract_no',$contractNo)->select('reference','job_contract_no')->first();
        $data['due_date']  = formatDate(date('Y-m-d', strtotime("+".$data['contractDataInvoice']->days_before_due."days")));
        //return view('admin.invoice.invoicePdf', $data);
        $pdf = PDF::loadView('admin.jobContractInvoice.invoicePrint', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('invoice_'.time().'.pdf',array("Attachment"=>0));
    }


    public function invoicePdf($contractNo,$invoiceNo)
    {
        $data['taxInfo'] = $this->sale->calculateTaxRow($invoiceNo, true);
        $data['contractDataInvoice'] = DB::table('job_contracts')
            ->where('job_contract_no', '=', $invoiceNo)
            ->leftJoin('location','location.loc_code','=','job_contracts.from_stk_loc')
            ->leftJoin('invoice_payment_terms','invoice_payment_terms.id','=','job_contracts.payment_term')
            ->select("job_contracts.*","location.location_name",'invoice_payment_terms.days_before_due')
            ->first();
        // d($data['saleDataInvoice'],1);
        $data['invoiceData'] = $this->jobContract->getJobContractByID($invoiceNo,$data['contractDataInvoice']->from_stk_loc);

        $data['customerInfo']  = DB::table('job_contracts')
            ->where('job_contracts.job_contract_no',$contractNo)
            ->leftjoin('debtors_master','debtors_master.debtor_no','=','job_contracts.debtor_no')
            ->leftjoin('cust_branch','cust_branch.branch_code','=','job_contracts.branch_id')
            ->leftjoin('countries','countries.id','=','cust_branch.shipping_country_id')
            ->select('debtors_master.debtor_no','debtors_master.name','debtors_master.phone','debtors_master.email','cust_branch.br_name','cust_branch.br_address','cust_branch.billing_street','cust_branch.billing_city','cust_branch.billing_state','cust_branch.billing_zip_code','countries.country','cust_branch.billing_country_id')
            ->first();
        $data['contractInfo']  = DB::table('job_contracts')->where('job_contract_no',$contractNo)->select('reference','job_contract_no')->first();
        $data['due_date']  = formatDate(date('Y-m-d', strtotime("+".$data['contractDataInvoice']->days_before_due."days")));
        //return view('admin.invoice.invoicePdf', $data);
        $pdf = PDF::loadView('admin.jobContractInvoice.invoicePdf', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('invoice_'.time().'.pdf',array("Attachment"=>0));
    }


    public function destroy($id)
    {
        if(isset($id)) {
            $record = \DB::table('job_contracts')->where('job_contract_no', $id)->first();
            if($record) {

                $invoice_id = $id;
                $order_id = $record->contract_reference_id;
                $invoice_reference = $record->reference;
                $order_reference = $record->contract_reference;

                DB::table('job_contracts')->where('job_contract_no', '=', $invoice_id)->delete();
                DB::table('job_contract_details')->where('job_contract_no', '=', $invoice_id)->delete();
                DB::table('job_contract_moves')->where('reference', '=', 'store_out_'.$invoice_id)->delete();
                DB::table('job_contract_payment_history')->where('invoice_reference', '=', $invoice_reference)->delete();

                \Session::flash('success',trans('message.success.delete_success'));
                return redirect()->intended('contract/sales/list');
            }
        }
    }


}
