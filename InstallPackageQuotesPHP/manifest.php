<?php

global $sugar_config;

$upload_dir = $sugar_config['upload_dir'];
$manifest = array(
 'acceptable_sugar_versions' => array( //sugar version module works
  'regex_matches' => array(
   0 => '6\.*'
  ),
 ),
 'acceptable_sugar_flavors' => array( //type of sugar plans module works on
  0 => 'CE',
  1 => 'PRO',
  2 => 'ENT',
 ), 
 'name'    => 'Custom PHP', //module name
 'description'  => 'Custom PHP for quotes module', //module description
 'is_uninstallable' => true, //set default none uninstallable
 'author'   => 'Rafael Silva', //auhtor name
 'published_date' => 'February 28, 2014', //date created
 'version'   => '1.0.0', //module version
 'type'    => 'module', //type 
 );
 
$installdefs = array(
 'id'  => 'CustomPHP', //custom module id
 'mkdir' => array( //dirs module will create
    array('path' => 'custom/modules/Quotes'), //create dir if not exist
 ), 
 'copy' => array( //copy module files to specific dirs
    array(
      'from' => '<basepath>/NewFiles/custom/modules/Quotes/logic_hooks.php', //from this module path
      'to'   => 'custom/modules/Quotes/logic_hooks.php', //to insert previous file call
    ),
	array(
      'from' => '<basepath>/NewFiles/custom/modules/Quotes/quotes_post.php', //from this module path
      'to'   => 'custom/modules/Quotes/quotes_post.php', //to insert previous file call
    ),
   ),
  'logic_hooks' => array( //add extra logic hooks to Quotes module
	array(
		'module' => 'Quotes', //define what module to load logic hook
		'hook' => 'after_save', //when logic hook must occur
		'order' => 27, //order to load hook
		'description' => 'Data keys clone post', //small desc about logic hook
		'file' => 'custom/modules/Quotes/quotes_post.php', //path to code of logic hook
		'class' => 'QuotesPost', //class in file to look for 
		'function' => 'clonePost', //method to cal from class
	),
  ),
 );
   
?>