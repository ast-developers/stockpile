<?php

use Illuminate\Database\Seeder;

class EmailTempDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emailTemp[0]['temp_id'] = 2;
        $emailTemp[0]['subject'] = 'Your Order # {order_reference_no} from {company_name} has been shipped';
        $emailTemp[0]['body'] = 'Hi {customer_name},<br><br>Thank you for your order. Here’s a brief overview of your shipment:<br>Sales Order # {order_reference_no} was packed on {packed_date} and shipped on {delivery_date}.<br> <br><b>Shipping address   </b><br><br>{shipping_street}<br>{shipping_city}<br>{shipping_state}<br>{shipping_zip_code}<br>{shipping_country}<br><br><b>Item Summery</b><br>{item_information}<br> <br>If you have any questions, please feel free to reply to this email.<br><br>Regards<br>{company_name}<br><br><br>';
        $emailTemp[0]['lang'] = 'en';
        $emailTemp[0]['lang_id'] = 1;

        $emailTemp[1]['temp_id'] = 2;
        $emailTemp[1]['subject'] = 'Subject';
        $emailTemp[1]['body'] = 'Body';
        $emailTemp[1]['lang'] = 'ar';
        $emailTemp[1]['lang_id'] = 2;

        $emailTemp[2]['temp_id'] = 2;
        $emailTemp[2]['subject'] = 'Subject';
        $emailTemp[2]['body'] = 'Body';
        $emailTemp[2]['lang'] = 'ch';
        $emailTemp[2]['lang_id'] = 3;

        $emailTemp[3]['temp_id'] = 2;
        $emailTemp[3]['subject'] = 'Subject';
        $emailTemp[3]['body'] = 'Body';
        $emailTemp[3]['lang'] = 'fr';
        $emailTemp[3]['lang_id'] = 4;

        $emailTemp[4]['temp_id'] = 2;
        $emailTemp[4]['subject'] = 'Subject';
        $emailTemp[4]['body'] = 'Body';
        $emailTemp[4]['lang'] = 'po';
        $emailTemp[4]['lang_id'] = 5;

        $emailTemp[5]['temp_id'] = 2;
        $emailTemp[5]['subject'] = 'Subject';
        $emailTemp[5]['body'] = 'Body';
        $emailTemp[5]['lang'] = 'rs';
        $emailTemp[5]['lang_id'] = 6;

        $emailTemp[6]['temp_id'] = 2;
        $emailTemp[6]['subject'] = 'Subject';
        $emailTemp[6]['body'] = 'Body';
        $emailTemp[6]['lang'] = 'sp';
        $emailTemp[6]['lang_id'] = 7;

        $emailTemp[42]['temp_id'] = 2;
        $emailTemp[42]['subject'] = 'Subject';
        $emailTemp[42]['body'] = 'Body';
        $emailTemp[42]['lang'] = 'tu';
        $emailTemp[42]['lang_id'] = 8;


        //----------1--------

        $emailTemp[7]['temp_id'] = 1;
        $emailTemp[7]['subject'] = 'Payment information for Order#{order_reference_no} and Invoice#{invoice_reference_no}.';
        $emailTemp[7]['body'] = '<p>Hi {customer_name},</p><p>Thank you for purchase our product and pay for this.</p><p>We just want to confirm a few details about payment information:</p><p><b>Customer Information</b></p><p>{billing_street}</p><p>{billing_city}</p><p>{billing_state}</p><p>{billing_zip_code}</p><p>{billing_country}<br></p><p><b>Payment Summary<br></b></p><p><b></b><i>Payment No : {payment_id}</i></p><p><i>Payment Date : {payment_date}&nbsp;</i></p><p><i>Payment Method : {payment_method} <br></i></p><p><i><b>Total Amount : {total_amount}</b></i></p><p><i>Order No : {order_reference_no}</i><br><i></i></p><p><i>Invoice No : {invoice_reference_no}</i><br></p><p><br></p><p>Regards,</p><p>{company_name}<br></p><br><br><br><br><br><br>';
        $emailTemp[7]['lang'] = 'en';
        $emailTemp[7]['lang_id'] = 1;

        $emailTemp[8]['temp_id'] = 1;
        $emailTemp[8]['subject'] = 'Subject';
        $emailTemp[8]['body'] = 'Body';
        $emailTemp[8]['lang'] = 'ar';
        $emailTemp[8]['lang_id'] = 2;

        $emailTemp[9]['temp_id'] = 1;
        $emailTemp[9]['subject'] = 'Subject';
        $emailTemp[9]['body'] = 'Body';
        $emailTemp[9]['lang'] = 'ch';
        $emailTemp[9]['lang_id'] = 3;

        $emailTemp[10]['temp_id'] = 1;
        $emailTemp[10]['subject'] = 'Subject';
        $emailTemp[10]['body'] = 'Body';
        $emailTemp[10]['lang'] = 'fr';
        $emailTemp[10]['lang_id'] = 4;

        $emailTemp[11]['temp_id'] = 1;
        $emailTemp[11]['subject'] = 'Subject';
        $emailTemp[11]['body'] = 'Body';
        $emailTemp[11]['lang'] = 'po';
        $emailTemp[11]['lang_id'] = 5;

        $emailTemp[12]['temp_id'] = 1;
        $emailTemp[12]['subject'] = 'Subject';
        $emailTemp[12]['body'] = 'Body';
        $emailTemp[12]['lang'] = 'rs';
        $emailTemp[12]['lang_id'] = 6;

        $emailTemp[13]['temp_id'] = 1;
        $emailTemp[13]['subject'] = 'Subject';
        $emailTemp[13]['body'] = 'Body';
        $emailTemp[13]['lang'] = 'sp';
        $emailTemp[13]['lang_id'] = 7;
        
        $emailTemp[43]['temp_id'] = 1;
        $emailTemp[43]['subject'] = 'Subject';
        $emailTemp[43]['body'] = 'Body';
        $emailTemp[43]['lang'] = 'tu';
        $emailTemp[43]['lang_id'] = 8;

        //----------3--------

        $emailTemp[14]['temp_id'] = 3;
        $emailTemp[14]['subject'] = 'Payment information for Order#{order_reference_no} and Invoice#{invoice_reference_no}.';
        $emailTemp[14]['body'] = '<p>Hi {customer_name},</p><p>Thank you for purchase our product and pay for this.</p><p>We just want to confirm a few details about payment information:</p><p><b>Customer Information</b></p><p>{billing_street}</p><p>{billing_city}</p><p>{billing_state}</p><p>{billing_zip_code}<br></p><p>{billing_country}<br>&nbsp; &nbsp; &nbsp; &nbsp; <br></p><p><b>Payment Summary<br></b></p><p><b></b><i>Payment No : {payment_id}</i></p><p><i>Payment Date : {payment_date}&nbsp;</i></p><p><i>Payment Method : {payment_method} <br></i></p><p><i><b>Total Amount : {total_amount}</b><br>Order No : {order_reference_no}<br>&nbsp;</i><i>Invoice No : {invoice_reference_no}<br>&nbsp;</i>Regards,</p><p>{company_name} <br></p><br>';
        $emailTemp[14]['lang'] = 'en';
        $emailTemp[14]['lang_id'] = 1;

        $emailTemp[15]['temp_id'] = 3;
        $emailTemp[15]['subject'] = 'Subject';
        $emailTemp[15]['body'] = 'Body';
        $emailTemp[15]['lang'] = 'ar';
        $emailTemp[15]['lang_id'] = 2;

        $emailTemp[16]['temp_id'] = 3;
        $emailTemp[16]['subject'] = 'Subject';
        $emailTemp[16]['body'] = 'Body';
        $emailTemp[16]['lang'] = 'ch';
        $emailTemp[16]['lang_id'] = 3;

        $emailTemp[17]['temp_id'] = 3;
        $emailTemp[17]['subject'] = 'Subject';
        $emailTemp[17]['body'] = 'Body';
        $emailTemp[17]['lang'] = 'fr';
        $emailTemp[17]['lang_id'] = 4;

        $emailTemp[18]['temp_id'] = 3;
        $emailTemp[18]['subject'] = 'Subject';
        $emailTemp[18]['body'] = 'Body';
        $emailTemp[18]['lang'] = 'po';
        $emailTemp[18]['lang_id'] = 5;

        $emailTemp[19]['temp_id'] = 3;
        $emailTemp[19]['subject'] = 'Subject';
        $emailTemp[19]['body'] = 'Body';
        $emailTemp[19]['lang'] = 'rs';
        $emailTemp[19]['lang_id'] = 6;

        $emailTemp[20]['temp_id'] = 3;
        $emailTemp[20]['subject'] = 'Subject';
        $emailTemp[20]['body'] = 'Body';
        $emailTemp[20]['lang'] = 'sp';
        $emailTemp[20]['lang_id'] = 7;

        $emailTemp[44]['temp_id'] = 3;
        $emailTemp[44]['subject'] = 'Subject';
        $emailTemp[44]['body'] = 'Body';
        $emailTemp[44]['lang'] = 'tu';
        $emailTemp[44]['lang_id'] = 8;

        //----------4--------

        $emailTemp[21]['temp_id'] = 4;
        $emailTemp[21]['subject'] = 'Your Invoice # {invoice_reference_no} for sales Order #{order_reference_no} from {company_name} has been created.';
        $emailTemp[21]['body'] = '<p>Hi {customer_name},</p><p>Thank you for your order. Here’s a brief overview of your invoice: Invoice #{invoice_reference_no} is for Sales Order #{order_reference_no}. The invoice total is {currency}{total_amount}, please pay before {due_date}.</p><p>If you have any questions, please feel free to reply to this email. </p><p><b>Billing address</b></p><p>&nbsp;{billing_street}</p><p>&nbsp;{billing_city}</p><p>&nbsp;{billing_state}</p><p>&nbsp;{billing_zip_code}</p><p>&nbsp;{billing_country}<br></p><p><br></p><p><b>Order summary<br></b></p><p><b></b>{invoice_summery}<br></p><p>Regards,</p><p>{company_name}<br></p><br><br>';
        $emailTemp[21]['lang'] = 'en';
        $emailTemp[21]['lang_id'] = 1;

        $emailTemp[22]['temp_id'] = 4;
        $emailTemp[22]['subject'] = 'Subject';
        $emailTemp[22]['body'] = 'Body';
        $emailTemp[22]['lang'] = 'ar';
        $emailTemp[22]['lang_id'] = 2;

        $emailTemp[23]['temp_id'] = 4;
        $emailTemp[23]['subject'] = 'Subject';
        $emailTemp[23]['body'] = 'Body';
        $emailTemp[23]['lang'] = 'ch';
        $emailTemp[23]['lang_id'] = 3;

        $emailTemp[24]['temp_id'] = 4;
        $emailTemp[24]['subject'] = 'Subject';
        $emailTemp[24]['body'] = 'Body';
        $emailTemp[24]['lang'] = 'fr';
        $emailTemp[24]['lang_id'] = 4;

        $emailTemp[25]['temp_id'] = 4;
        $emailTemp[25]['subject'] = 'Subject';
        $emailTemp[25]['body'] = 'Body';
        $emailTemp[25]['lang'] = 'po';
        $emailTemp[25]['lang_id'] = 5;

        $emailTemp[26]['temp_id'] = 4;
        $emailTemp[26]['subject'] = 'Subject';
        $emailTemp[26]['body'] = 'Body';
        $emailTemp[26]['lang'] = 'rs';
        $emailTemp[26]['lang_id'] = 6;

        $emailTemp[27]['temp_id'] = 4;
        $emailTemp[27]['subject'] = 'Subject';
        $emailTemp[27]['body'] = 'Body';
        $emailTemp[27]['lang'] = 'sp';
        $emailTemp[27]['lang_id'] = 7;

        $emailTemp[45]['temp_id'] = 4;
        $emailTemp[45]['subject'] = 'Subject';
        $emailTemp[45]['body'] = 'Body';
        $emailTemp[45]['lang'] = 'tu';
        $emailTemp[45]['lang_id'] = 8;

        //----------5--------

        $emailTemp[28]['temp_id'] = 5;
        $emailTemp[28]['subject'] = 'Your Order# {order_reference_no} from {company_name} has been created.';
        $emailTemp[28]['body'] = '<p>Hi {customer_name},</p><p>Thank you for your order. Here’s a brief overview of your Order #{order_reference_no} that was created on {order_date}. The order total is {currency}{total_amount}.</p><p>If you have any questions, please feel free to reply to this email. </p><p><b>Billing address</b></p><p>&nbsp;{billing_street}</p><p>&nbsp;{billing_city}</p><p>&nbsp;{billing_state}</p><p>&nbsp;{billing_zip_code}</p><p>&nbsp;{billing_country}<br></p><p><br></p><p><b>Order summary<br></b></p><p><b></b>{order_summery}<br></p><p>Regards,</p><p>{company_name}</p><br><br>';
        $emailTemp[28]['lang'] = 'en';
        $emailTemp[28]['lang_id'] = 1;

        $emailTemp[29]['temp_id'] = 5;
        $emailTemp[29]['subject'] = 'Subject';
        $emailTemp[29]['body'] = 'Body';
        $emailTemp[29]['lang'] = 'ar';
        $emailTemp[29]['lang_id'] = 2;

        $emailTemp[30]['temp_id'] = 5;
        $emailTemp[30]['subject'] = 'Subject';
        $emailTemp[30]['body'] = 'Body';
        $emailTemp[30]['lang'] = 'ch';
        $emailTemp[30]['lang_id'] = 3;

        $emailTemp[31]['temp_id'] = 5;
        $emailTemp[31]['subject'] = 'Subject';
        $emailTemp[31]['body'] = 'Body';
        $emailTemp[31]['lang'] = 'fr';
        $emailTemp[31]['lang_id'] = 4;

        $emailTemp[32]['temp_id'] = 5;
        $emailTemp[32]['subject'] = 'Subject';
        $emailTemp[32]['body'] = 'Body';
        $emailTemp[32]['lang'] = 'po';
        $emailTemp[32]['lang_id'] = 5;

        $emailTemp[33]['temp_id'] = 5;
        $emailTemp[33]['subject'] = 'Subject';
        $emailTemp[33]['body'] = 'Body';
        $emailTemp[33]['lang'] = 'rs';
        $emailTemp[33]['lang_id'] = 6;

        $emailTemp[34]['temp_id'] = 5;
        $emailTemp[34]['subject'] = 'Subject';
        $emailTemp[34]['body'] = 'Body';
        $emailTemp[34]['lang'] = 'sp';
        $emailTemp[34]['lang_id'] = 7;
        
        $emailTemp[46]['temp_id'] = 5;
        $emailTemp[46]['subject'] = 'Subject';
        $emailTemp[46]['body'] = 'Body';
        $emailTemp[46]['lang'] = 'tu';
        $emailTemp[46]['lang_id'] = 8;

        // Email templates 6
        $emailTemp[35]['temp_id'] = 6;
        $emailTemp[35]['subject'] = 'Your Order # {order_reference_no} from {company_name} has been packed';
        $emailTemp[35]['body'] = 'Hi {customer_name},<br><br>Thank you for your order. Here’s a brief overview of your shipment:<br>Sales Order # {order_reference_no} was packed on {packed_date}.<br> <br><b>Shipping address   </b><br><br>{shipping_street}<br>{shipping_city}<br>{shipping_state}<br>{shipping_zip_code}<br>{shipping_country}<br><br><b>Item Summery</b><br>{item_information}<br> <br>If you have any questions, please feel free to reply to this email.<br><br>Regards<br>{company_name}<br><br><br>';
        $emailTemp[35]['lang'] = 'en';
        $emailTemp[35]['lang_id'] = 1;

        $emailTemp[36]['temp_id'] = 6;
        $emailTemp[36]['subject'] = 'Subject';
        $emailTemp[36]['body'] = 'Body';
        $emailTemp[36]['lang'] = 'ar';
        $emailTemp[36]['lang_id'] = 2;

        $emailTemp[37]['temp_id'] = 6;
        $emailTemp[37]['subject'] = 'Subject';
        $emailTemp[37]['body'] = 'Body';
        $emailTemp[37]['lang'] = 'ch';
        $emailTemp[37]['lang_id'] = 3;

        $emailTemp[38]['temp_id'] = 6;
        $emailTemp[38]['subject'] = 'Subject';
        $emailTemp[38]['body'] = 'Body';
        $emailTemp[38]['lang'] = 'fr';
        $emailTemp[38]['lang_id'] = 4;

        $emailTemp[39]['temp_id'] = 6;
        $emailTemp[39]['subject'] = 'Subject';
        $emailTemp[39]['body'] = 'Body';
        $emailTemp[39]['lang'] = 'po';
        $emailTemp[39]['lang_id'] = 5;

        $emailTemp[40]['temp_id'] = 6;
        $emailTemp[40]['subject'] = 'Subject';
        $emailTemp[40]['body'] = 'Body';
        $emailTemp[40]['lang'] = 'rs';
        $emailTemp[40]['lang_id'] = 6;

        $emailTemp[41]['temp_id'] = 6;
        $emailTemp[41]['subject'] = 'Subject';
        $emailTemp[41]['body'] = 'Body';
        $emailTemp[41]['lang'] = 'sp';
        $emailTemp[41]['lang_id'] = 7;        
        
        $emailTemp[47]['temp_id'] = 6;
        $emailTemp[47]['subject'] = 'Subject';
        $emailTemp[47]['body'] = 'Body';
        $emailTemp[47]['lang'] = 'tu';
        $emailTemp[47]['lang_id'] = 8;

        DB::table('email_temp_details')->truncate();
		DB::table('email_temp_details')->insert($emailTemp);
    }
}