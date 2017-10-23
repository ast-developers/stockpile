<?php

use Illuminate\Database\Seeder;

class PreferenceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pre[0]['category'] = 'preference';
        $pre[0]['field'] = 'row_per_page';
        $pre[0]['value'] = '25';

        $pre[1]['category'] = 'preference';
        $pre[1]['field'] = 'date_format';
        $pre[1]['value'] = '1';

        $pre[2]['category'] = 'preference';
        $pre[2]['field'] = 'date_sepa';
        $pre[2]['value'] = '-';

        $pre[3]['category'] = 'preference';
        $pre[3]['field'] = 'soft_name';
        $pre[3]['value'] = 'Stockpile';

        $pre[4]['category'] = 'company';
        $pre[4]['field'] = 'site_short_name';
        $pre[4]['value'] = 'SP';

        $pre[5]['category'] = 'preference';
        $pre[5]['field'] = 'percentage';
        $pre[5]['value'] = '0';

        $pre[6]['category'] = 'preference';
        $pre[6]['field'] = 'quantity';
        $pre[6]['value'] = '0';

        $pre[7]['category'] = 'preference';
        $pre[7]['field'] = 'date_format_type';
        $pre[7]['value'] = 'dd-mm-yyyy';

        $pre[8]['category'] = 'company';
        $pre[8]['field'] = 'company_name';
        $pre[8]['value'] = 'Stockpile';

        $pre[9]['category'] = 'company';
        $pre[9]['field'] = 'company_email';
        $pre[9]['value'] = 'admin@techvill.net';

        $pre[10]['category'] = 'company';
        $pre[10]['field'] = 'company_phone';
        $pre[10]['value'] = '123465798';

        $pre[11]['category'] = 'company';
        $pre[11]['field'] = 'company_street';
        $pre[11]['value'] = 'City Hall Park Path';

        $pre[12]['category'] = 'company';
        $pre[12]['field'] = 'company_city';
        $pre[12]['value'] = 'New York';

        $pre[13]['category'] = 'company';
        $pre[13]['field'] = 'company_state';
        $pre[13]['value'] = 'New York';

        $pre[14]['category'] = 'company';
        $pre[14]['field'] = 'company_zipCode';
        $pre[14]['value'] = '10007';

        $pre[15]['category'] = 'company';
        $pre[15]['field'] = 'company_country_id';
        $pre[15]['value'] = 'United States';

        $pre[16]['category'] = 'company';
        $pre[16]['field'] = 'dflt_lang';
        $pre[16]['value'] = 'en';

        $pre[17]['category'] = 'company';
        $pre[17]['field'] = 'dflt_currency_id';
        $pre[17]['value'] = 1;

        $pre[18]['category'] = 'company';
        $pre[18]['field'] = 'sates_type_id';
        $pre[18]['value'] = 1;

        DB::table('preference')->truncate();
		DB::table('preference')->insert($pre);
    }
}