

CREATE TABLE IF NOT EXISTS `backup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO backup VALUES("1","2017-01-09-102924.sql","2017-01-09 10:29:24","");





CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=243 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO countries VALUES("1","United States","US");
INSERT INTO countries VALUES("2","Canada","CA");
INSERT INTO countries VALUES("3","Afghanistan","AF");
INSERT INTO countries VALUES("4","Albania","AL");
INSERT INTO countries VALUES("5","Algeria","DZ");
INSERT INTO countries VALUES("6","American Samoa","AS");
INSERT INTO countries VALUES("7","Andorra","AD");
INSERT INTO countries VALUES("8","Angola","AO");
INSERT INTO countries VALUES("9","Anguilla","AI");
INSERT INTO countries VALUES("10","Antarctica","AQ");
INSERT INTO countries VALUES("11","Antigua and/or Barbuda","AG");
INSERT INTO countries VALUES("12","Argentina","AR");
INSERT INTO countries VALUES("13","Armenia","AM");
INSERT INTO countries VALUES("14","Aruba","AW");
INSERT INTO countries VALUES("15","Australia","AU");
INSERT INTO countries VALUES("16","Austria","AT");
INSERT INTO countries VALUES("17","Azerbaijan","AZ");
INSERT INTO countries VALUES("18","Bahamas","BS");
INSERT INTO countries VALUES("19","Bahrain","BH");
INSERT INTO countries VALUES("20","Bangladesh","BD");
INSERT INTO countries VALUES("21","Barbados","BB");
INSERT INTO countries VALUES("22","Belarus","BY");
INSERT INTO countries VALUES("23","Belgium","BE");
INSERT INTO countries VALUES("24","Belize","BZ");
INSERT INTO countries VALUES("25","Benin","BJ");
INSERT INTO countries VALUES("26","Bermuda","BM");
INSERT INTO countries VALUES("27","Bhutan","BT");
INSERT INTO countries VALUES("28","Bolivia","BO");
INSERT INTO countries VALUES("29","Bosnia and Herzegovina","BA");
INSERT INTO countries VALUES("30","Botswana","BW");
INSERT INTO countries VALUES("31","Bouvet Island","BV");
INSERT INTO countries VALUES("32","Brazil","BR");
INSERT INTO countries VALUES("33","British lndian Ocean Territory","IO");
INSERT INTO countries VALUES("34","Brunei Darussalam","BN");
INSERT INTO countries VALUES("35","Bulgaria","BG");
INSERT INTO countries VALUES("36","Burkina Faso","BF");
INSERT INTO countries VALUES("37","Burundi","BI");
INSERT INTO countries VALUES("38","Cambodia","KH");
INSERT INTO countries VALUES("39","Cameroon","CM");
INSERT INTO countries VALUES("40","Cape Verde","CV");
INSERT INTO countries VALUES("41","Cayman Islands","KY");
INSERT INTO countries VALUES("42","Central African Republic","CF");
INSERT INTO countries VALUES("43","Chad","TD");
INSERT INTO countries VALUES("44","Chile","CL");
INSERT INTO countries VALUES("45","China","CN");
INSERT INTO countries VALUES("46","Christmas Island","CX");
INSERT INTO countries VALUES("47","Cocos (Keeling) Islands","CC");
INSERT INTO countries VALUES("48","Colombia","CO");
INSERT INTO countries VALUES("49","Comoros","KM");
INSERT INTO countries VALUES("50","Congo","CG");
INSERT INTO countries VALUES("51","Cook Islands","CK");
INSERT INTO countries VALUES("52","Costa Rica","CR");
INSERT INTO countries VALUES("53","Croatia (Hrvatska)","HR");
INSERT INTO countries VALUES("54","Cuba","CU");
INSERT INTO countries VALUES("55","Cyprus","CY");
INSERT INTO countries VALUES("56","Czech Republic","CZ");
INSERT INTO countries VALUES("57","Democratic Republic of Congo","CD");
INSERT INTO countries VALUES("58","Denmark","DK");
INSERT INTO countries VALUES("59","Djibouti","DJ");
INSERT INTO countries VALUES("60","Dominica","DM");
INSERT INTO countries VALUES("61","Dominican Republic","DO");
INSERT INTO countries VALUES("62","East Timor","TP");
INSERT INTO countries VALUES("63","Ecudaor","EC");
INSERT INTO countries VALUES("64","Egypt","EG");
INSERT INTO countries VALUES("65","El Salvador","SV");
INSERT INTO countries VALUES("66","Equatorial Guinea","GQ");
INSERT INTO countries VALUES("67","Eritrea","ER");
INSERT INTO countries VALUES("68","Estonia","EE");
INSERT INTO countries VALUES("69","Ethiopia","ET");
INSERT INTO countries VALUES("70","Falkland Islands (Malvinas)","FK");
INSERT INTO countries VALUES("71","Faroe Islands","FO");
INSERT INTO countries VALUES("72","Fiji","FJ");
INSERT INTO countries VALUES("73","Finland","FI");
INSERT INTO countries VALUES("74","France","FR");
INSERT INTO countries VALUES("75","France, Metropolitan","FX");
INSERT INTO countries VALUES("76","French Guiana","GF");
INSERT INTO countries VALUES("77","French Polynesia","PF");
INSERT INTO countries VALUES("78","French Southern Territories","TF");
INSERT INTO countries VALUES("79","Gabon","GA");
INSERT INTO countries VALUES("80","Gambia","GM");
INSERT INTO countries VALUES("81","Georgia","GE");
INSERT INTO countries VALUES("82","Germany","DE");
INSERT INTO countries VALUES("83","Ghana","GH");
INSERT INTO countries VALUES("84","Gibraltar","GI");
INSERT INTO countries VALUES("85","Greece","GR");
INSERT INTO countries VALUES("86","Greenland","GL");
INSERT INTO countries VALUES("87","Grenada","GD");
INSERT INTO countries VALUES("88","Guadeloupe","GP");
INSERT INTO countries VALUES("89","Guam","GU");
INSERT INTO countries VALUES("90","Guatemala","GT");
INSERT INTO countries VALUES("91","Guinea","GN");
INSERT INTO countries VALUES("92","Guinea-Bissau","GW");
INSERT INTO countries VALUES("93","Guyana","GY");
INSERT INTO countries VALUES("94","Haiti","HT");
INSERT INTO countries VALUES("95","Heard and Mc Donald Islands","HM");
INSERT INTO countries VALUES("96","Honduras","HN");
INSERT INTO countries VALUES("97","Hong Kong","HK");
INSERT INTO countries VALUES("98","Hungary","HU");
INSERT INTO countries VALUES("99","Iceland","IS");
INSERT INTO countries VALUES("100","India","IN");
INSERT INTO countries VALUES("101","Indonesia","ID");
INSERT INTO countries VALUES("102","Iran (Islamic Republic of)","IR");
INSERT INTO countries VALUES("103","Iraq","IQ");
INSERT INTO countries VALUES("104","Ireland","IE");
INSERT INTO countries VALUES("105","Israel","IL");
INSERT INTO countries VALUES("106","Italy","IT");
INSERT INTO countries VALUES("107","Ivory Coast","CI");
INSERT INTO countries VALUES("108","Jamaica","JM");
INSERT INTO countries VALUES("109","Japan","JP");
INSERT INTO countries VALUES("110","Jordan","JO");
INSERT INTO countries VALUES("111","Kazakhstan","KZ");
INSERT INTO countries VALUES("112","Kenya","KE");
INSERT INTO countries VALUES("113","Kiribati","KI");
INSERT INTO countries VALUES("114","Korea, Democratic People\'s Republic of","KP");
INSERT INTO countries VALUES("115","Korea, Republic of","KR");
INSERT INTO countries VALUES("116","Kuwait","KW");
INSERT INTO countries VALUES("117","Kyrgyzstan","KG");
INSERT INTO countries VALUES("118","Lao People\'s Democratic Republic","LA");
INSERT INTO countries VALUES("119","Latvia","LV");
INSERT INTO countries VALUES("120","Lebanon","LB");
INSERT INTO countries VALUES("121","Lesotho","LS");
INSERT INTO countries VALUES("122","Liberia","LR");
INSERT INTO countries VALUES("123","Libyan Arab Jamahiriya","LY");
INSERT INTO countries VALUES("124","Liechtenstein","LI");
INSERT INTO countries VALUES("125","Lithuania","LT");
INSERT INTO countries VALUES("126","Luxembourg","LU");
INSERT INTO countries VALUES("127","Macau","MO");
INSERT INTO countries VALUES("128","Macedonia","MK");
INSERT INTO countries VALUES("129","Madagascar","MG");
INSERT INTO countries VALUES("130","Malawi","MW");
INSERT INTO countries VALUES("131","Malaysia","MY");
INSERT INTO countries VALUES("132","Maldives","MV");
INSERT INTO countries VALUES("133","Mali","ML");
INSERT INTO countries VALUES("134","Malta","MT");
INSERT INTO countries VALUES("135","Marshall Islands","MH");
INSERT INTO countries VALUES("136","Martinique","MQ");
INSERT INTO countries VALUES("137","Mauritania","MR");
INSERT INTO countries VALUES("138","Mauritius","MU");
INSERT INTO countries VALUES("139","Mayotte","TY");
INSERT INTO countries VALUES("140","Mexico","MX");
INSERT INTO countries VALUES("141","Micronesia, Federated States of","FM");
INSERT INTO countries VALUES("142","Moldova, Republic of","MD");
INSERT INTO countries VALUES("143","Monaco","MC");
INSERT INTO countries VALUES("144","Mongolia","MN");
INSERT INTO countries VALUES("145","Montserrat","MS");
INSERT INTO countries VALUES("146","Morocco","MA");
INSERT INTO countries VALUES("147","Mozambique","MZ");
INSERT INTO countries VALUES("148","Myanmar","MM");
INSERT INTO countries VALUES("149","Namibia","NA");
INSERT INTO countries VALUES("150","Nauru","NR");
INSERT INTO countries VALUES("151","Nepal","NP");
INSERT INTO countries VALUES("152","Netherlands","NL");
INSERT INTO countries VALUES("153","Netherlands Antilles","AN");
INSERT INTO countries VALUES("154","New Caledonia","NC");
INSERT INTO countries VALUES("155","New Zealand","NZ");
INSERT INTO countries VALUES("156","Nicaragua","NI");
INSERT INTO countries VALUES("157","Niger","NE");
INSERT INTO countries VALUES("158","Nigeria","NG");
INSERT INTO countries VALUES("159","Niue","NU");
INSERT INTO countries VALUES("160","Norfork Island","NF");
INSERT INTO countries VALUES("161","Northern Mariana Islands","MP");
INSERT INTO countries VALUES("162","Norway","NO");
INSERT INTO countries VALUES("163","Oman","OM");
INSERT INTO countries VALUES("164","Pakistan","PK");
INSERT INTO countries VALUES("165","Palau","PW");
INSERT INTO countries VALUES("166","Panama","PA");
INSERT INTO countries VALUES("167","Papua New Guinea","PG");
INSERT INTO countries VALUES("168","Paraguay","PY");
INSERT INTO countries VALUES("169","Peru","PE");
INSERT INTO countries VALUES("170","Philippines","PH");
INSERT INTO countries VALUES("171","Pitcairn","PN");
INSERT INTO countries VALUES("172","Poland","PL");
INSERT INTO countries VALUES("173","Portugal","PT");
INSERT INTO countries VALUES("174","Puerto Rico","PR");
INSERT INTO countries VALUES("175","Qatar","QA");
INSERT INTO countries VALUES("176","Republic of South Sudan","SS");
INSERT INTO countries VALUES("177","Reunion","RE");
INSERT INTO countries VALUES("178","Romania","RO");
INSERT INTO countries VALUES("179","Russian Federation","RU");
INSERT INTO countries VALUES("180","Rwanda","RW");
INSERT INTO countries VALUES("181","Saint Kitts and Nevis","KN");
INSERT INTO countries VALUES("182","Saint Lucia","LC");
INSERT INTO countries VALUES("183","Saint Vincent and the Grenadines","VC");
INSERT INTO countries VALUES("184","Samoa","WS");
INSERT INTO countries VALUES("185","San Marino","SM");
INSERT INTO countries VALUES("186","Sao Tome and Principe","ST");
INSERT INTO countries VALUES("187","Saudi Arabia","SA");
INSERT INTO countries VALUES("188","Senegal","SN");
INSERT INTO countries VALUES("189","Serbia","RS");
INSERT INTO countries VALUES("190","Seychelles","SC");
INSERT INTO countries VALUES("191","Sierra Leone","SL");
INSERT INTO countries VALUES("192","Singapore","SG");
INSERT INTO countries VALUES("193","Slovakia","SK");
INSERT INTO countries VALUES("194","Slovenia","SI");
INSERT INTO countries VALUES("195","Solomon Islands","SB");
INSERT INTO countries VALUES("196","Somalia","SO");
INSERT INTO countries VALUES("197","South Africa","ZA");
INSERT INTO countries VALUES("198","South Georgia South Sandwich Islands","GS");
INSERT INTO countries VALUES("199","Spain","ES");
INSERT INTO countries VALUES("200","Sri Lanka","LK");
INSERT INTO countries VALUES("201","St. Helena","SH");
INSERT INTO countries VALUES("202","St. Pierre and Miquelon","PM");
INSERT INTO countries VALUES("203","Sudan","SD");
INSERT INTO countries VALUES("204","Suriname","SR");
INSERT INTO countries VALUES("205","Svalbarn and Jan Mayen Islands","SJ");
INSERT INTO countries VALUES("206","Swaziland","SZ");
INSERT INTO countries VALUES("207","Sweden","SE");
INSERT INTO countries VALUES("208","Switzerland","CH");
INSERT INTO countries VALUES("209","Syrian Arab Republic","SY");
INSERT INTO countries VALUES("210","Taiwan","TW");
INSERT INTO countries VALUES("211","Tajikistan","TJ");
INSERT INTO countries VALUES("212","Tanzania, United Republic of","TZ");
INSERT INTO countries VALUES("213","Thailand","TH");
INSERT INTO countries VALUES("214","Togo","TG");
INSERT INTO countries VALUES("215","Tokelau","TK");
INSERT INTO countries VALUES("216","Tonga","TO");
INSERT INTO countries VALUES("217","Trinidad and Tobago","TT");
INSERT INTO countries VALUES("218","Tunisia","TN");
INSERT INTO countries VALUES("219","Turkey","TR");
INSERT INTO countries VALUES("220","Turkmenistan","TM");
INSERT INTO countries VALUES("221","Turks and Caicos Islands","TC");
INSERT INTO countries VALUES("222","Tuvalu","TV");
INSERT INTO countries VALUES("223","Uganda","UG");
INSERT INTO countries VALUES("224","Ukraine","UA");
INSERT INTO countries VALUES("225","United Arab Emirates","AE");
INSERT INTO countries VALUES("226","United Kingdom","GB");
INSERT INTO countries VALUES("227","United States minor outlying islands","UM");
INSERT INTO countries VALUES("228","Uruguay","UY");
INSERT INTO countries VALUES("229","Uzbekistan","UZ");
INSERT INTO countries VALUES("230","Vanuatu","VU");
INSERT INTO countries VALUES("231","Vatican City State","VA");
INSERT INTO countries VALUES("232","Venezuela","VE");
INSERT INTO countries VALUES("233","Vietnam","VN");
INSERT INTO countries VALUES("234","Virgin Islands (British)","VG");
INSERT INTO countries VALUES("235","Virgin Islands (U.S.)","VI");
INSERT INTO countries VALUES("236","Wallis and Futuna Islands","WF");
INSERT INTO countries VALUES("237","Western Sahara","EH");
INSERT INTO countries VALUES("238","Yemen","YE");
INSERT INTO countries VALUES("239","Yugoslavia","YU");
INSERT INTO countries VALUES("240","Zaire","ZR");
INSERT INTO countries VALUES("241","Zambia","ZM");
INSERT INTO countries VALUES("242","Zimbabwe","ZW");





CREATE TABLE IF NOT EXISTS `currency` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `symbol` char(5) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO currency VALUES("1","USD","$");





CREATE TABLE IF NOT EXISTS `cust_branch` (
  `branch_code` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `debtor_no` int(11) NOT NULL,
  `br_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `br_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `br_contact` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `billing_street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `billing_city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `billing_state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `billing_zip_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `billing_country_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_zip_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_country_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`branch_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE IF NOT EXISTS `debtors_master` (
  `debtor_no` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sales_type` int(11) NOT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`debtor_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE IF NOT EXISTS `email_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email_protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_encryption` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `smtp_host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `smtp_port` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `smtp_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `smtp_username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `smtp_password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO email_config VALUES("1","smtp","tls","smtp.gmail.com","587","stockpile.techvill@gmail.com","stockpile.techvill@gmail.com","xgldhlpedszmglvj","stockpile.techvill@gmail.com","stockpile.techvill@gmail.com");





CREATE TABLE IF NOT EXISTS `email_temp_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `temp_id` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `lang` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lang_id` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO email_temp_details VALUES("1","2","Your Order # {order_reference_no} from {company_name} has been shipped","Hi {customer_name},<br><br>Thank you for your order. Here’s a brief overview of your shipment:<br>Sales Order # {order_reference_no} was packed on {packed_date} and shipped on {delivery_date}.<br> <br><b>Shipping address   </b><br><br>{shipping_street}<br>{shipping_city}<br>{shipping_state}<br>{shipping_zip_code}<br>{shipping_country}<br><br><b>Item Summery</b><br>{item_information}<br> <br>If you have any questions, please feel free to reply to this email.<br><br>Regards<br>{company_name}<br><br><br>","en","1");
INSERT INTO email_temp_details VALUES("2","2","Subject","Body","ar","2");
INSERT INTO email_temp_details VALUES("3","2","Subject","Body","ch","3");
INSERT INTO email_temp_details VALUES("4","2","Subject","Body","fr","4");
INSERT INTO email_temp_details VALUES("5","2","Subject","Body","po","5");
INSERT INTO email_temp_details VALUES("6","2","Subject","Body","rs","6");
INSERT INTO email_temp_details VALUES("7","2","Subject","Body","sp","7");
INSERT INTO email_temp_details VALUES("8","2","Subject","Body","tu","8");
INSERT INTO email_temp_details VALUES("9","1","Payment information for Order#{order_reference_no} and Invoice#{invoice_reference_no}.","<p>Hi {customer_name},</p><p>Thank you for purchase our product and pay for this.</p><p>We just want to confirm a few details about payment information:</p><p><b>Customer Information</b></p><p>{billing_street}</p><p>{billing_city}</p><p>{billing_state}</p><p>{billing_zip_code}</p><p>{billing_country}<br></p><p><b>Payment Summary<br></b></p><p><b></b><i>Payment No : {payment_id}</i></p><p><i>Payment Date : {payment_date}&nbsp;</i></p><p><i>Payment Method : {payment_method} <br></i></p><p><i><b>Total Amount : {total_amount}</b></i></p><p><i>Order No : {order_reference_no}</i><br><i></i></p><p><i>Invoice No : {invoice_reference_no}</i><br></p><p><br></p><p>Regards,</p><p>{company_name}<br></p><br><br><br><br><br><br>","en","1");
INSERT INTO email_temp_details VALUES("10","1","Subject","Body","ar","2");
INSERT INTO email_temp_details VALUES("11","1","Subject","Body","ch","3");
INSERT INTO email_temp_details VALUES("12","1","Subject","Body","fr","4");
INSERT INTO email_temp_details VALUES("13","1","Subject","Body","po","5");
INSERT INTO email_temp_details VALUES("14","1","Subject","Body","rs","6");
INSERT INTO email_temp_details VALUES("15","1","Subject","Body","sp","7");
INSERT INTO email_temp_details VALUES("16","1","Subject","Body","tu","8");
INSERT INTO email_temp_details VALUES("17","3","Payment information for Order#{order_reference_no} and Invoice#{invoice_reference_no}.","<p>Hi {customer_name},</p><p>Thank you for purchase our product and pay for this.</p><p>We just want to confirm a few details about payment information:</p><p><b>Customer Information</b></p><p>{billing_street}</p><p>{billing_city}</p><p>{billing_state}</p><p>{billing_zip_code}<br></p><p>{billing_country}<br>&nbsp; &nbsp; &nbsp; &nbsp; <br></p><p><b>Payment Summary<br></b></p><p><b></b><i>Payment No : {payment_id}</i></p><p><i>Payment Date : {payment_date}&nbsp;</i></p><p><i>Payment Method : {payment_method} <br></i></p><p><i><b>Total Amount : {total_amount}</b><br>Order No : {order_reference_no}<br>&nbsp;</i><i>Invoice No : {invoice_reference_no}<br>&nbsp;</i>Regards,</p><p>{company_name} <br></p><br>","en","1");
INSERT INTO email_temp_details VALUES("18","3","Subject","Body","ar","2");
INSERT INTO email_temp_details VALUES("19","3","Subject","Body","ch","3");
INSERT INTO email_temp_details VALUES("20","3","Subject","Body","fr","4");
INSERT INTO email_temp_details VALUES("21","3","Subject","Body","po","5");
INSERT INTO email_temp_details VALUES("22","3","Subject","Body","rs","6");
INSERT INTO email_temp_details VALUES("23","3","Subject","Body","sp","7");
INSERT INTO email_temp_details VALUES("24","3","Subject","Body","tu","8");
INSERT INTO email_temp_details VALUES("25","4","Your Invoice # {invoice_reference_no} for sales Order #{order_reference_no} from {company_name} has been created.","<p>Hi {customer_name},</p><p>Thank you for your order. Here’s a brief overview of your invoice: Invoice #{invoice_reference_no} is for Sales Order #{order_reference_no}. The invoice total is {currency}{total_amount}, please pay before {due_date}.</p><p>If you have any questions, please feel free to reply to this email. </p><p><b>Billing address</b></p><p>&nbsp;{billing_street}</p><p>&nbsp;{billing_city}</p><p>&nbsp;{billing_state}</p><p>&nbsp;{billing_zip_code}</p><p>&nbsp;{billing_country}<br></p><p><br></p><p><b>Order summary<br></b></p><p><b></b>{invoice_summery}<br></p><p>Regards,</p><p>{company_name}<br></p><br><br>","en","1");
INSERT INTO email_temp_details VALUES("26","4","Subject","Body","ar","2");
INSERT INTO email_temp_details VALUES("27","4","Subject","Body","ch","3");
INSERT INTO email_temp_details VALUES("28","4","Subject","Body","fr","4");
INSERT INTO email_temp_details VALUES("29","4","Subject","Body","po","5");
INSERT INTO email_temp_details VALUES("30","4","Subject","Body","rs","6");
INSERT INTO email_temp_details VALUES("31","4","Subject","Body","sp","7");
INSERT INTO email_temp_details VALUES("32","4","Subject","Body","tu","8");
INSERT INTO email_temp_details VALUES("33","5","Your Order# {order_reference_no} from {company_name} has been created.","<p>Hi {customer_name},</p><p>Thank you for your order. Here’s a brief overview of your Order #{order_reference_no} that was created on {order_date}. The order total is {currency}{total_amount}.</p><p>If you have any questions, please feel free to reply to this email. </p><p><b>Billing address</b></p><p>&nbsp;{billing_street}</p><p>&nbsp;{billing_city}</p><p>&nbsp;{billing_state}</p><p>&nbsp;{billing_zip_code}</p><p>&nbsp;{billing_country}<br></p><p><br></p><p><b>Order summary<br></b></p><p><b></b>{order_summery}<br></p><p>Regards,</p><p>{company_name}</p><br><br>","en","1");
INSERT INTO email_temp_details VALUES("34","5","Subject","Body","ar","2");
INSERT INTO email_temp_details VALUES("35","5","Subject","Body","ch","3");
INSERT INTO email_temp_details VALUES("36","5","Subject","Body","fr","4");
INSERT INTO email_temp_details VALUES("37","5","Subject","Body","po","5");
INSERT INTO email_temp_details VALUES("38","5","Subject","Body","rs","6");
INSERT INTO email_temp_details VALUES("39","5","Subject","Body","sp","7");
INSERT INTO email_temp_details VALUES("40","5","Subject","Body","tu","8");
INSERT INTO email_temp_details VALUES("41","6","Your Order # {order_reference_no} from {company_name} has been packed","Hi {customer_name},<br><br>Thank you for your order. Here’s a brief overview of your shipment:<br>Sales Order # {order_reference_no} was packed on {packed_date}.<br> <br><b>Shipping address   </b><br><br>{shipping_street}<br>{shipping_city}<br>{shipping_state}<br>{shipping_zip_code}<br>{shipping_country}<br><br><b>Item Summery</b><br>{item_information}<br> <br>If you have any questions, please feel free to reply to this email.<br><br>Regards<br>{company_name}<br><br><br>","en","1");
INSERT INTO email_temp_details VALUES("42","6","Subject","Body","ar","2");
INSERT INTO email_temp_details VALUES("43","6","Subject","Body","ch","3");
INSERT INTO email_temp_details VALUES("44","6","Subject","Body","fr","4");
INSERT INTO email_temp_details VALUES("45","6","Subject","Body","po","5");
INSERT INTO email_temp_details VALUES("46","6","Subject","Body","rs","6");
INSERT INTO email_temp_details VALUES("47","6","Subject","Body","sp","7");
INSERT INTO email_temp_details VALUES("48","6","Subject","Body","tu","8");





CREATE TABLE IF NOT EXISTS `invoice_payment_terms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `terms` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `days_before_due` int(11) NOT NULL,
  `defaults` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO invoice_payment_terms VALUES("1","Cash on deleivery","0","1");
INSERT INTO invoice_payment_terms VALUES("2","Net15","15","0");
INSERT INTO invoice_payment_terms VALUES("3","Net30","30","0");





CREATE TABLE IF NOT EXISTS `item_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` smallint(6) NOT NULL,
  `item_image` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO item_code VALUES("1","ACER","Acer Laptop","1","","0","0","","");
INSERT INTO item_code VALUES("2","APPLE","Iphone 7+","1","","0","0","","");
INSERT INTO item_code VALUES("3","ASUS","Asus Laptop","1","","0","0","","");
INSERT INTO item_code VALUES("4","HP","HP Pro Book","1","","0","0","","");
INSERT INTO item_code VALUES("5","LENEVO","LED TV","1","","0","0","","");
INSERT INTO item_code VALUES("6","LG","LG Refrigeretor","1","","0","0","","");
INSERT INTO item_code VALUES("7","SAMSUNG","Samsung G7","1","","0","0","","");
INSERT INTO item_code VALUES("8","SINGER","Singer Refrigerator","1","","0","0","","");
INSERT INTO item_code VALUES("9","SONY","Sony experia 5","1","","0","0","","");
INSERT INTO item_code VALUES("10","WALTON","Walton Primo GH","1","","0","0","","");





CREATE TABLE IF NOT EXISTS `item_tax_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tax_rate` double(8,2) NOT NULL,
  `exempt` tinyint(4) NOT NULL,
  `defaults` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO item_tax_types VALUES("1","Tax Exempt","0.00","1","0");
INSERT INTO item_tax_types VALUES("2","Sales Tax","15.00","0","1");
INSERT INTO item_tax_types VALUES("3","Purchases Tax","15.00","0","0");





CREATE TABLE IF NOT EXISTS `item_unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `abbr` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO item_unit VALUES("1","each","Each","0","2017-01-09 09:51:17","");





CREATE TABLE IF NOT EXISTS `location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `loc_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `location_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `delivery_address` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO location VALUES("1","PL","Primary Location","Primary Location","","","","Primary Location","0","2017-01-09 09:51:17","");





CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO migrations VALUES("2014_10_12_100000_create_password_resets_table","1");
INSERT INTO migrations VALUES("2016_08_30_100832_create_users_table","1");
INSERT INTO migrations VALUES("2016_08_30_104058_create_security_role_table","1");
INSERT INTO migrations VALUES("2016_08_30_104506_create_stock_category_table","1");
INSERT INTO migrations VALUES("2016_08_30_105339_create_location_table","1");
INSERT INTO migrations VALUES("2016_08_30_110408_create_item_code_table","1");
INSERT INTO migrations VALUES("2016_08_30_114231_create_item_unit_table","1");
INSERT INTO migrations VALUES("2016_09_02_070031_create_stock_master_table","1");
INSERT INTO migrations VALUES("2016_09_20_123717_create_stock_move_table","1");
INSERT INTO migrations VALUES("2016_10_05_113244_create_debtor_master_table","1");
INSERT INTO migrations VALUES("2016_10_05_113333_create_sales_orders_table","1");
INSERT INTO migrations VALUES("2016_10_05_113356_create_sales_order_details_table","1");
INSERT INTO migrations VALUES("2016_10_18_060431_create_supplier_table","1");
INSERT INTO migrations VALUES("2016_10_18_063931_create_purch_order_table","1");
INSERT INTO migrations VALUES("2016_10_18_064211_create_purch_order_detail_table","1");
INSERT INTO migrations VALUES("2016_11_15_121343_create_preference_table","1");
INSERT INTO migrations VALUES("2016_12_01_130110_create_shipment_table","1");
INSERT INTO migrations VALUES("2016_12_01_130443_create_shipment_details_table","1");
INSERT INTO migrations VALUES("2016_12_03_051429_create_sale_price_table","1");
INSERT INTO migrations VALUES("2016_12_03_052017_create_sales_types_table","1");
INSERT INTO migrations VALUES("2016_12_03_061206_create_purchase_price_table","1");
INSERT INTO migrations VALUES("2016_12_03_062131_create_payment_term_table","1");
INSERT INTO migrations VALUES("2016_12_03_062247_create_payment_history_table","1");
INSERT INTO migrations VALUES("2016_12_03_062932_create_item_tax_type_table","1");
INSERT INTO migrations VALUES("2016_12_03_063827_create_invoice_payment_term_table","1");
INSERT INTO migrations VALUES("2016_12_03_064157_create_email_temp_details_table","1");
INSERT INTO migrations VALUES("2016_12_03_064747_create_email_config_table","1");
INSERT INTO migrations VALUES("2016_12_03_065532_create_cust_branch_table","1");
INSERT INTO migrations VALUES("2016_12_03_065915_create_currency_table","1");
INSERT INTO migrations VALUES("2016_12_03_070030_create_country_table","1");
INSERT INTO migrations VALUES("2016_12_03_070030_create_stock_transfer_table","1");
INSERT INTO migrations VALUES("2016_12_03_071018_create_backup_table","1");





CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE IF NOT EXISTS `payment_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment_type_id` smallint(6) NOT NULL,
  `order_reference` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_reference` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `amount` double DEFAULT '0',
  `person_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `reference` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE IF NOT EXISTS `payment_terms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `defaults` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO payment_terms VALUES("1","Bank","1");
INSERT INTO payment_terms VALUES("2","Cash","0");





CREATE TABLE IF NOT EXISTS `preference` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO preference VALUES("1","preference","row_per_page","10");
INSERT INTO preference VALUES("2","preference","date_format","0");
INSERT INTO preference VALUES("3","preference","date_sepa","-");
INSERT INTO preference VALUES("4","preference","soft_name","Stockpile");
INSERT INTO preference VALUES("5","company","site_short_name","SP");
INSERT INTO preference VALUES("6","preference","percentage","0");
INSERT INTO preference VALUES("7","preference","quantity","0");
INSERT INTO preference VALUES("8","preference","date_format_type","yyyy-mm-dd");
INSERT INTO preference VALUES("9","company","company_name","Stockpile");
INSERT INTO preference VALUES("10","company","company_email","demo@demo.com");
INSERT INTO preference VALUES("11","company","company_phone","123465798");
INSERT INTO preference VALUES("12","company","company_street","default");
INSERT INTO preference VALUES("13","company","company_city","default");
INSERT INTO preference VALUES("14","company","company_state","default");
INSERT INTO preference VALUES("15","company","company_zipCode","default");
INSERT INTO preference VALUES("16","company","company_country_id","United States");
INSERT INTO preference VALUES("17","company","dflt_lang","en");
INSERT INTO preference VALUES("18","company","dflt_currency_id","1");
INSERT INTO preference VALUES("19","company","sates_type_id","1");





CREATE TABLE IF NOT EXISTS `purch_order_details` (
  `po_detail_item` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL,
  `item_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `qty_invoiced` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `act_price` double NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL,
  `std_cost_unit` double NOT NULL DEFAULT '0',
  `quantity_ordered` double NOT NULL DEFAULT '0',
  `quantity_received` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`po_detail_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE IF NOT EXISTS `purch_orders` (
  `order_no` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL,
  `comments` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `ord_date` date NOT NULL,
  `reference` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `requisition_no` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `into_stock_location` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `delivery_address` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `total` double NOT NULL DEFAULT '0',
  `tax_included` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE IF NOT EXISTS `purchase_prices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stock_id` char(30) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL DEFAULT '0',
  `suppliers_uom` char(30) COLLATE utf8_unicode_ci NOT NULL,
  `conversion_factor` double DEFAULT '1',
  `supplier_description` char(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO purchase_prices VALUES("1","ACER","50","","1","");
INSERT INTO purchase_prices VALUES("2","APPLE","50","","1","");
INSERT INTO purchase_prices VALUES("3","ASUS","50","","1","");
INSERT INTO purchase_prices VALUES("4","HP","50","","1","");
INSERT INTO purchase_prices VALUES("5","LENEVO","50","","1","");
INSERT INTO purchase_prices VALUES("6","LG","50","","1","");
INSERT INTO purchase_prices VALUES("7","SAMSUNG","50","","1","");
INSERT INTO purchase_prices VALUES("8","SINGER","50","","1","");
INSERT INTO purchase_prices VALUES("9","SONY","50","","1","");
INSERT INTO purchase_prices VALUES("10","WALTON","50","","1","");





CREATE TABLE IF NOT EXISTS `sale_prices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sales_type_id` int(11) NOT NULL,
  `curr_abrev` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO sale_prices VALUES("1","ACER","1","USD","60");
INSERT INTO sale_prices VALUES("2","APPLE","1","USD","60");
INSERT INTO sale_prices VALUES("3","ASUS","1","USD","60");
INSERT INTO sale_prices VALUES("4","HP","1","USD","60");
INSERT INTO sale_prices VALUES("5","LENEVO","1","USD","60");
INSERT INTO sale_prices VALUES("6","LG","1","USD","60");
INSERT INTO sale_prices VALUES("7","SAMSUNG","1","USD","60");
INSERT INTO sale_prices VALUES("8","SINGER","1","USD","60");
INSERT INTO sale_prices VALUES("9","SONY","1","USD","60");
INSERT INTO sale_prices VALUES("10","WALTON","1","USD","60");
INSERT INTO sale_prices VALUES("11","ACER","2","USD","55");
INSERT INTO sale_prices VALUES("12","APPLE","2","USD","55");
INSERT INTO sale_prices VALUES("13","ASUS","2","USD","55");
INSERT INTO sale_prices VALUES("14","HP","2","USD","55");
INSERT INTO sale_prices VALUES("15","LENEVO","2","USD","55");
INSERT INTO sale_prices VALUES("16","LG","2","USD","55");
INSERT INTO sale_prices VALUES("17","SAMSUNG","2","USD","55");
INSERT INTO sale_prices VALUES("18","SINGER","2","USD","55");
INSERT INTO sale_prices VALUES("19","SONY","2","USD","55");
INSERT INTO sale_prices VALUES("20","WALTON","2","USD","55");





CREATE TABLE IF NOT EXISTS `sales_order_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL,
  `trans_type` int(11) NOT NULL,
  `stock_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tax_type_id` tinyint(4) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `unit_price` double NOT NULL DEFAULT '0',
  `qty_sent` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `shipment_qty` double NOT NULL DEFAULT '0',
  `discount_percent` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE IF NOT EXISTS `sales_orders` (
  `order_no` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `trans_type` int(11) NOT NULL,
  `debtor_no` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `version` tinyint(4) NOT NULL,
  `reference` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `customer_ref` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_reference_id` int(11) NOT NULL,
  `order_reference` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ord_date` date NOT NULL,
  `order_type` int(11) NOT NULL,
  `delivery_address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deliver_to` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_stk_loc` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `payment_id` tinyint(4) NOT NULL,
  `total` double NOT NULL DEFAULT '0',
  `paid_amount` double NOT NULL DEFAULT '0',
  `choices` enum('no','partial_created','full_created') COLLATE utf8_unicode_ci NOT NULL,
  `payment_term` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE IF NOT EXISTS `sales_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sales_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tax_included` int(11) NOT NULL,
  `factor` double NOT NULL,
  `defaults` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO sales_types VALUES("1","Retail","1","0","1");
INSERT INTO sales_types VALUES("2","Wholesale","0","0","0");





CREATE TABLE IF NOT EXISTS `security_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sections` text COLLATE utf8_unicode_ci,
  `areas` text COLLATE utf8_unicode_ci,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO security_role VALUES("1","System Administrator","System Administrator","a:21:{s:8:\"category\";s:3:\"100\";s:4:\"unit\";s:3:\"600\";s:3:\"loc\";s:3:\"200\";s:4:\"item\";s:3:\"300\";s:4:\"user\";s:3:\"400\";s:4:\"role\";s:3:\"500\";s:8:\"customer\";s:3:\"700\";s:5:\"sales\";s:3:\"800\";s:8:\"purchese\";s:3:\"900\";s:8:\"supplier\";s:4:\"1000\";s:8:\"transfer\";s:4:\"1100\";s:5:\"order\";s:4:\"1200\";s:8:\"shipment\";s:4:\"1300\";s:7:\"payment\";s:4:\"1400\";s:6:\"backup\";s:4:\"1500\";s:5:\"email\";s:4:\"1600\";s:3:\"tax\";s:4:\"1900\";s:9:\"salestype\";s:4:\"2000\";s:10:\"currencies\";s:4:\"2100\";s:11:\"paymentterm\";s:4:\"2200\";s:13:\"paymentmethod\";s:4:\"2300\";}","a:62:{s:7:\"cat_add\";s:3:\"101\";s:8:\"cat_edit\";s:3:\"102\";s:10:\"cat_delete\";s:3:\"103\";s:8:\"unit_add\";s:3:\"601\";s:9:\"unit_edit\";s:3:\"602\";s:11:\"unit_delete\";s:3:\"603\";s:7:\"loc_add\";s:3:\"201\";s:8:\"loc_edit\";s:3:\"202\";s:10:\"loc_delete\";s:3:\"203\";s:8:\"item_add\";s:3:\"301\";s:9:\"item_edit\";s:3:\"302\";s:11:\"item_delete\";s:3:\"303\";s:9:\"item_copy\";s:3:\"304\";s:8:\"user_add\";s:3:\"401\";s:9:\"user_edit\";s:3:\"402\";s:11:\"user_delete\";s:3:\"403\";s:9:\"user_role\";s:3:\"501\";s:12:\"customer_add\";s:3:\"701\";s:13:\"customer_edit\";s:3:\"702\";s:15:\"customer_delete\";s:3:\"703\";s:9:\"sales_add\";s:3:\"801\";s:10:\"sales_edit\";s:3:\"802\";s:12:\"sales_delete\";s:3:\"803\";s:12:\"purchese_add\";s:3:\"901\";s:13:\"purchese_edit\";s:3:\"902\";s:15:\"purchese_delete\";s:3:\"903\";s:12:\"supplier_add\";s:4:\"1001\";s:13:\"supplier_edit\";s:4:\"1002\";s:15:\"supplier_delete\";s:4:\"1003\";s:12:\"transfer_add\";s:4:\"1101\";s:13:\"transfer_edit\";s:4:\"1102\";s:15:\"transfer_delete\";s:4:\"1103\";s:9:\"order_add\";s:4:\"1201\";s:10:\"order_edit\";s:4:\"1202\";s:12:\"order_delete\";s:4:\"1203\";s:12:\"shipment_add\";s:4:\"1301\";s:13:\"shipment_edit\";s:4:\"1302\";s:15:\"shipment_delete\";s:4:\"1303\";s:11:\"payment_add\";s:4:\"1401\";s:12:\"payment_edit\";s:4:\"1402\";s:14:\"payment_delete\";s:4:\"1403\";s:10:\"backup_add\";s:4:\"1501\";s:15:\"backup_download\";s:4:\"1502\";s:9:\"email_add\";s:4:\"1601\";s:9:\"emailtemp\";s:4:\"1700\";s:10:\"preference\";s:4:\"1800\";s:7:\"tax_add\";s:4:\"1901\";s:8:\"tax_edit\";s:4:\"1902\";s:10:\"tax_delete\";s:4:\"1903\";s:13:\"salestype_add\";s:4:\"2001\";s:14:\"salestype_edit\";s:4:\"2002\";s:16:\"salestype_delete\";s:4:\"2003\";s:14:\"currencies_add\";s:4:\"2101\";s:15:\"currencies_edit\";s:4:\"2102\";s:17:\"currencies_delete\";s:4:\"2103\";s:15:\"paymentterm_add\";s:4:\"2201\";s:16:\"paymentterm_edit\";s:4:\"2202\";s:18:\"paymentterm_delete\";s:4:\"2203\";s:17:\"paymentmethod_add\";s:4:\"2301\";s:18:\"paymentmethod_edit\";s:4:\"2302\";s:20:\"paymentmethod_delete\";s:4:\"2303\";s:14:\"companysetting\";s:4:\"2400\";}","0","2017-01-09 09:51:17","");





CREATE TABLE IF NOT EXISTS `shipment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL,
  `trans_type` int(11) NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `packed_date` date NOT NULL,
  `delivery_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE IF NOT EXISTS `shipment_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shipment_id` int(11) NOT NULL,
  `order_no` int(11) NOT NULL,
  `stock_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tax_type_id` tinyint(4) NOT NULL,
  `unit_price` double NOT NULL,
  `quantity` double NOT NULL,
  `discount_percent` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE IF NOT EXISTS `stock_category` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dflt_units` int(11) NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO stock_category VALUES("1","Default Category","1","0","2017-01-09 09:51:17","");





CREATE TABLE IF NOT EXISTS `stock_master` (
  `stock_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `tax_type_id` int(11) NOT NULL,
  `description` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `long_description` text COLLATE utf8_unicode_ci NOT NULL,
  `units` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO stock_master VALUES("ACER","1","2","Acer Laptop","Laptop","Each","0","0","","");
INSERT INTO stock_master VALUES("APPLE","1","2","Iphone 7+","Mobile","Each","0","0","","");
INSERT INTO stock_master VALUES("ASUS","1","2","Asus Laptop","Laptop","Each","0","0","","");
INSERT INTO stock_master VALUES("HP","1","2","HP Pro Book","Laptop","Each","0","0","","");
INSERT INTO stock_master VALUES("LENEVO","1","2","LED TV","TV","Each","0","0","","");
INSERT INTO stock_master VALUES("LG","1","2","LG Refrigeretor","Fridge","Each","0","0","","");
INSERT INTO stock_master VALUES("SAMSUNG","1","2","Samsung G7","Mobile","Each","0","0","","");
INSERT INTO stock_master VALUES("SINGER","1","2","Singer Refrigerator","Fridge","Each","0","0","","");
INSERT INTO stock_master VALUES("SONY","1","2","Sony experia 5","Mobile","Each","0","0","","");
INSERT INTO stock_master VALUES("WALTON","1","2","Walton Primo GH","Mobile","Each","0","0","","");





CREATE TABLE IF NOT EXISTS `stock_moves` (
  `trans_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `order_no` int(11) NOT NULL,
  `trans_type` smallint(6) NOT NULL DEFAULT '0',
  `loc_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `tran_date` date NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  `order_reference` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `reference` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_reference_id` int(11) NOT NULL,
  `transfer_id` int(11) DEFAULT NULL,
  `note` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `qty` double NOT NULL DEFAULT '0',
  `price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE IF NOT EXISTS `stock_transfer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `destination` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL,
  `qty` int(11) NOT NULL,
  `transfer_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE IF NOT EXISTS `suppliers` (
  `supplier_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `supp_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `real_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '1',
  `phone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `picture` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO users VALUES("1","","$2y$10$PzbYPnyAcX2h9l8dknwsqeTiBMXLEZE304f8vrzDLwDwnNEkTKbQG","Admin","1","","admin@admin.com","","0","","2017-01-09 09:51:32","2017-01-09 09:51:32");



