<?php

$hook_version = 1;

$hook_array = Array();
$hook_array['after_save'] = Array();
$hook_array['after_save'][] = Array(
	1, //processing index. For sorting the array
	'after_save quotespost', //label. A string value to identify the hook
	'custom/modules/Quotes/quotes_post.php', //the php file where your class is located
	'QuotesPost', //the class the method is in
	'clonePost' //the method to call
);

?>