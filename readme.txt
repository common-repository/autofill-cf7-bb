=== autofill-CF7-BB ===
Contributors: Billyben
Donate link:  https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=legendreben%40hotmail%2efr&item_name=BillyBen%20Ringt%20WordPress%20Widget&no_shipping=0&no_note=1&tax=0&currency_code=EUR&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: contact form 7, autofill, url, GET,select,checkboxe,radio,text
Requires at least: 2.8.1
Tested up to: 3.3.2
Stable tag: 1.0.1

Add shortcode for fields autofill of Contact Form 7 plugin by URL get variable, by Id or by value, or add new value(s).


== Description ==

autofill-CF7-BB let you autofill contact form 7 fields (input text, radio, select, checkboxes, textarea) with url GET variables. It add a shortcode to write in the "contact form 7 template editor" for each field.
For drop down menu fields and radio/checkboxes you can specify wether you would select it by Id (from 0) or by value. You can also populate each field with new values from URL Get var, or replace firstly defined values.

**Requirements** 
PHP 5+ (use <a href='http://sourceforge.net/projects/simplehtmldom/' target='_blank'>Simple HTML Dom</a> from <a href='https://sourceforge.net/projects/php-html/' target='_blank'>Jose Solorzano</a>)

No javascript (excpet for option page...)


== Installation ==

1. Upload the entire `autofill-CF7-BB` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

For usage, please refer to the "Other note" section.


== Frequently Asked Questions ==

= Q/ It add lines (eg br tag) between fields? =
A- Yes, in the CF7 template editor, write shortcode in 1 line... It should come from CF7 parser (actually, I don't know ! )

== Options description ==
= USES =
First of all, go into the template éditor of contact form 7, and choose the template which you want to "autofill".

shortcode : [AFCF_BB getvar="myVar"][CF7 field][/AFCF_BB]

* getvar : the URL GET variable name used to fill the CF7 field
eg : [AFCF_BB getvar="myId"][select whatevername "Roger" "Jessica" "Steve" "Suzie"][/AFCF_BB]

and url as : http://mysite.com/?page_id=1&myId=2

will select id 2 for the select field so "Steve" (numbering start by 0 (not 1)).

Other method :
* meth="value" : will selet by value
* meth="add" : will add at the bottom new input
* meth="rep" : will replace current values

For "add" and "rep" you could preselect values by adding "*" before values, eg :

http://mysite.com/?page_id=1&addval=*Steeve$Rebecca$*julie

For more detail, consult the <a href="http://asblog.etherocliquecite.eu/?page_id=774&lang=en" target="_blank">plugin page</a> or see it in the plugin option page of wordpress (when installed).
for any question, please contact me at http://asblog.etherocliquecite.eu

== Changelog ==

= 1.0.1 =
* add textarea support
* add preselected value by "*" character

= 1.0.0 = Public release