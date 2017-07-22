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
  $folderDestination=$photoFolderHistory."/";

  if(isset($_GET[$parameterName]) && !empty($_GET[$parameterName])){
    $fileName=$_GET[$parameterName];
    $fileSource=$globalPath.$folderSource.$fileName;
    $fileDestination=$globalPath.$folderDestination.$fileName;

    if(!file_exists($fileSource)){
      header("HTTP/1.0 404 file was not found");
      return;
    }

    if( rename($fileSource, $fileDestination) ){
      // file was renamed successfully
      header("HTTP/1.0 200");
    }else{
        // can't rename file
        header("HTTP/1.0 404 Rename error");
    }

  } else {
    // need to specify request parameter
    header("HTTP/1.0 400 Bad request");
  }
 ?>
