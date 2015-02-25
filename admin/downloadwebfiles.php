<?php
error_reporting(E_ALL ^ E_NOTICE);
	session_start();
if($_SESSION['user']=='bbqadmin')
{

    $zipname = 'adfiles.zip';
    $zip = new ZipArchive;
    $zip->open($zipname, ZipArchive::CREATE);
    if ($handle = opendir('.')) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") { // && !strstr($entry,'.php')
            $zip->addFile($entry);
        }
      }
      closedir($handle);
    }

    $zip->close();

    header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename='adfiles.zip'");
    header('Content-Length: ' . filesize($zipname));
    header("Location: adfiles.zip");
}
else
{
	header("Location: ../index.html");
}
?>
