<?php

$opts = getopt('',array('export:', 'destination:'));

if(!file_exists($opts['export'])){
	echo "Could not find Shopify product export file to parse. Use the --export switch to specify the product export file for parsing.\n";
	exit(-1);
}

if(!strlen($opts['destination'])){
	echo "You must specify a destination directory to save all the images in. Use the --destination switch to specify the directory.\n";
	exit(-1);	
}

$fh = fopen($opts['export'], 'r');
ini_set('auto_detect_line_endings',1);

$dir = $opts['destination'];
shell_exec("rm -rf {$dir}");

if(!mkdir($dir)){
	echo "Could not create destination directory for writing.";
	exit(-1);
}

$first = TRUE;
while($row = fgetcsv($fh)){
	if($first){
		$first = FALSE;
		continue;		
	}
	$handle = $row[0];
	if(strlen($row[24])){		
		$url = $row[24];
		download_imag_url($dir, $handle, $url);		
	}

	if(strlen($row[42])){
		$variant_url = $row[42];
		download_imag_url($dir, $handle, $variant_url);
	}
}

function download_imag_url($dir, $handle, $url){
	static $handles = array();
	static $downloaded_md5 = array();


	$url = str_replace('https://', 'http://', $url);

	if(!isset($handles[$handle])){
		$handles[$handle] = 1;
	}

	$info = parse_url($url);
	$path = $info['path'];
	$ext = pathinfo($path, PATHINFO_EXTENSION);


	## Download the file
	$filename = $dir . '/' . $handle . '_' . $handles[$handle] . '.' . $ext;	
	$data = file_get_contents($url);
	$md5 = md5($data);
	if(isset($downloaded_md5[$md5])){
		echo "Already downloaded " . $url . "\n";
		return;
	}
	$downloaded_md5[$md5] = TRUE;
	echo "Downloading " . $url . " and saving to " . $filename . "\n";
	file_put_contents($filename, $data);
	$handles[$handle] += 1;
}