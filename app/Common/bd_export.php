<?php
//  导出word文件
function export_doc($filename){
	ini_set("memory_limit","500M");
	header("Content-Type: application/msword; charset=UTF-8");  
	header("Content-Disposition:filename=".$filename.".doc");
	//  宽度建议630
}
//  导出excel文件
function export_excel($filename,$content){
	ini_set("memory_limit","500M");
	header("Content-Type: application/vnd.ms-excel; charset=UTF-8");  
	header("Content-Disposition:filename=".$filename.".xls");
	$file_str = iconv("utf-8","GB18030",$content);
	echo $file_str;
}