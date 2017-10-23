<?php

use Illuminate\Database\Seeder;

class SecurityRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$securityRole = [
            'role' => 'System Administrator',
            'description' => 'System Administrator',
            'sections' => 'a:21:{s:8:"category";s:3:"100";s:4:"unit";s:3:"600";s:3:"loc";s:3:"200";s:4:"item";s:3:"300";s:4:"user";s:3:"400";s:4:"role";s:3:"500";s:8:"customer";s:3:"700";s:5:"sales";s:3:"800";s:8:"purchese";s:3:"900";s:8:"supplier";s:4:"1000";s:8:"transfer";s:4:"1100";s:5:"order";s:4:"1200";s:8:"shipment";s:4:"1300";s:7:"payment";s:4:"1400";s:6:"backup";s:4:"1500";s:5:"email";s:4:"1600";s:3:"tax";s:4:"1900";s:9:"salestype";s:4:"2000";s:10:"currencies";s:4:"2100";s:11:"paymentterm";s:4:"2200";s:13:"paymentmethod";s:4:"2300";}',
            'areas' => 'a:62:{s:7:"cat_add";s:3:"101";s:8:"cat_edit";s:3:"102";s:10:"cat_delete";s:3:"103";s:8:"unit_add";s:3:"601";s:9:"unit_edit";s:3:"602";s:11:"unit_delete";s:3:"603";s:7:"loc_add";s:3:"201";s:8:"loc_edit";s:3:"202";s:10:"loc_delete";s:3:"203";s:8:"item_add";s:3:"301";s:9:"item_edit";s:3:"302";s:11:"item_delete";s:3:"303";s:9:"item_copy";s:3:"304";s:8:"user_add";s:3:"401";s:9:"user_edit";s:3:"402";s:11:"user_delete";s:3:"403";s:9:"user_role";s:3:"501";s:12:"customer_add";s:3:"701";s:13:"customer_edit";s:3:"702";s:15:"customer_delete";s:3:"703";s:9:"sales_add";s:3:"801";s:10:"sales_edit";s:3:"802";s:12:"sales_delete";s:3:"803";s:12:"purchese_add";s:3:"901";s:13:"purchese_edit";s:3:"902";s:15:"purchese_delete";s:3:"903";s:12:"supplier_add";s:4:"1001";s:13:"supplier_edit";s:4:"1002";s:15:"supplier_delete";s:4:"1003";s:12:"transfer_add";s:4:"1101";s:13:"transfer_edit";s:4:"1102";s:15:"transfer_delete";s:4:"1103";s:9:"order_add";s:4:"1201";s:10:"order_edit";s:4:"1202";s:12:"order_delete";s:4:"1203";s:12:"shipment_add";s:4:"1301";s:13:"shipment_edit";s:4:"1302";s:15:"shipment_delete";s:4:"1303";s:11:"payment_add";s:4:"1401";s:12:"payment_edit";s:4:"1402";s:14:"payment_delete";s:4:"1403";s:10:"backup_add";s:4:"1501";s:15:"backup_download";s:4:"1502";s:9:"email_add";s:4:"1601";s:9:"emailtemp";s:4:"1700";s:10:"preference";s:4:"1800";s:7:"tax_add";s:4:"1901";s:8:"tax_edit";s:4:"1902";s:10:"tax_delete";s:4:"1903";s:13:"salestype_add";s:4:"2001";s:14:"salestype_edit";s:4:"2002";s:16:"salestype_delete";s:4:"2003";s:14:"currencies_add";s:4:"2101";s:15:"currencies_edit";s:4:"2102";s:17:"currencies_delete";s:4:"2103";s:15:"paymentterm_add";s:4:"2201";s:16:"paymentterm_edit";s:4:"2202";s:18:"paymentterm_delete";s:4:"2203";s:17:"paymentmethod_add";s:4:"2301";s:18:"paymentmethod_edit";s:4:"2302";s:20:"paymentmethod_delete";s:4:"2303";s:14:"companysetting";s:4:"2400";}',
            'created_at' => date('Y-m-d H:i:s')
        ];

        DB::table('security_role')->truncate();
		DB::table('security_role')->insert($securityRole);
    }
}