<?php
    $photoFolder="/home/technik7/public_html/house";
    $photoFolderHistory="/home/technik7/public_html/house_history";
    $photoExtension=".jpg";

    function endsWith($haystack, $needle) {
      // search forward starting from end minus needle length characters
      return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }

?>

<?php

  $parameterName="file";
  // $globalPath="/var/www/html/security/";
  $globalPath="";
  $folderSource=$photoFolder."/";

  if(isset($_GET[$parameterName]) && !empty($_GET[$parameterName])){
    $fileName=$_GET[$parameterName];
    $fileSource=$globalPath.$folderSource.$fileName;

    if(!file_exists($fileSource)){
      header("HTTP/1.0 404 file was not found");
      return;
    }

    $dir = opendir($photoFolder);
    $files = array();
    while ($files[] = readdir($dir));
      sort($files);
    closedir($dir);

    foreach ($files as $file){
      if(endsWith($file, $photoExtension)){
        if($file==$fileName){
          break;
        }
        unlink($folderSource.$file);
      }
    }

  } else {
    // need to specify request parameter
    header("HTTP/1.0 400 Bad request");
  }
 ?>
