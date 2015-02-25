<?php
	error_reporting(E_ALL ^ E_NOTICE);
	session_start();
if($_SESSION['user']=='bbqadmin')
{

    $zipname = 'allwebsite.zip';
    $zip = new ZipArchive;
    $zip->open($zipname, ZipArchive::CREATE);
    if ($handle = opendir('.')) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && filetype() != 'dir') { // && !strstr($entry,'.php')
            $zip->addFile($entry);
        }
      }
	  $zip->addFile('admin/adpanel.php');
      closedir($handle);
    }

    $zip->close();

    header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename='allwebsite.zip'");
    header('Content-Length: ' . filesize($zipname));
    header("Location: allwebsite.zip");
}
else
{
	header("Location: /index.html");
}
?>
