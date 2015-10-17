<?php
    $photoFolder="/home/technik7/public_html/house";
    $photoFolderHistory="/home/technik7/public_html/house_history";
    $photoExtension=".jpg";
?>

<!DOCTYPE html>
<html>
 <head>
  <title>Security house</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">

  <!-- jQuery -->
  <script src="js/jquery-1.10.2.min.js"></script>

  <!-- Fotorama -->
  <link href="js/fotorama.css" rel="stylesheet">
  <script src="js/fotorama.js"></script>
  <script src="js/moment.min.js"></script>
  <style media="screen">
  .time_positive   {
    color:green;
    font-family:verdana;
    font-size:300%;
  }
  .time_negative    {
    color:red;
    font-family:verdana;
    font-size:300%;
  }
  </style>

  <!-- Just don’t want to repeat this prefix in every img[src] -->
  <script>
    var pathToApp="";
    /** extension of the name of images */
    var imageExtension=<?php echo "\"".$photoExtension."\""  ?>;
    /** length of time stamp of image */
    var timeStampLength=14;
    /** additional prefix of the name of the file*/
    var timeStampLengthMess=2;
    /** name of the last image, which was shown on fotorama */
    var lastActivatedImage="";

    function onChangeImageOnFotorama(newImageName){
      printDifference(lastActivatedImage, newImageName);
      lastActivatedImage=newImageName;
      printDate(newImageName);
    }

    var DATETIME_FORMAT="YYYYMMDDHHmmss";
    
    function printDate(newImage){
      var newTimestamp=getTimeStamp(newImage);
      var time=newTimestamp.substring(8,14);
      $("#display").html(time.substring(0,2)+":"+time.substring(2,4)+":"+time.substring(4,6));
    }

    /** calculate difference and print it on the page */
    function printDifference(previousImage, newImage){
      var previousTimestamp=getTimeStamp(previousImage);
      var newTimestamp=getTimeStamp(newImage);
      var previousDate=moment().format(previousTimestamp, DATETIME_FORMAT);
      var newDate=moment().format(newTimestamp, DATETIME_FORMAT);

      var ms;
      if(newDate>previousDate){
        ms = moment(newTimestamp,DATETIME_FORMAT).diff(moment(previousTimestamp,DATETIME_FORMAT));
      }else {
        ms = moment(previousTimestamp,DATETIME_FORMAT).diff(moment(newTimestamp,DATETIME_FORMAT));
      }
      var d = moment.duration(ms);
      var s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");
      // console.log("s: "+s);
      displayTime(s, newDate>previousDate);
    }

    function displayTime(message, isPositive){
      $("#diff").text(message);
      $("#diff").removeAttr('class');
      if(isPositive){
        $("#diff").attr('class', 'time_positive');
      }else{
        $("#diff").attr('class', 'time_negative');
      }
    }

    /** read time stamp from file like: house2015101323010801.jpg */
    function getTimeStamp(imageName){
       var imageNameWithoutExtension=imageName.substring(1, imageName.length-imageExtension.length-timeStampLengthMess);
       return imageNameWithoutExtension.substring(imageNameWithoutExtension.length-timeStampLength, imageNameWithoutExtension.length);
    }

    // read active image which shown right now
    function getActiveImage(){
      var image=$(".fotorama__active");
      return image.children(0).children(0);
    }

    // change listener
    $(function () {
        $('.fotorama')
            // Listen to the events
            .on('fotorama:showend ',
                function (e, fotorama, extra) {
                  onChangeImageOnFotorama(fotorama.activeFrame.img);
                }
            )
            // Initialize fotorama manually
            .fotorama();
      });

    function getActiveImageName(){     
      var imagePath=getActiveImage().attr("src");
      
      if(imagePath.indexOf('/')<0){
      	return imagePath;
      }else{
      	return imagePath.substring(imagePath.lastIndexOf('/')+1,imagePath.length);
      }
    }

    // control functions
    /**
    remove single file
    */
    function removeImage(){
      // get request "remove"
      var fileName=getActiveImageName();
      $.get(pathToApp+"remove.php?file="+fileName,
          function( data ) {
            location.reload();
          });
    }

    function removeImageBeforeCurrent(){
      // get request "remove"
      var fileName=getActiveImageName();
      $.get(pathToApp+"removeEarlier.php?file="+fileName,
          function( data ) {
            location.reload();
          });
    }

    function copyActiveImageToHistory(){
      // get request "move to history"
      $( "#lastMovedToHistory" ).src="";
      var filePath=getActiveImage().attr("src");
      var fileName=getActiveImageName();
      $.get(pathToApp+"movedToHistory.php?file="+fileName,
            function( data ) {
              $( "#lastMovedToHistory" ).attr("src",filePath);
            })
    }
  </script>
</head>

<body>
  <!-- history block -->
  <button onclick="copyActiveImageToHistory()">move to history:</button>
  &nbsp; Last added to History:
  <img id="lastMovedToHistory" width="125" />
  <br />
  <div id="display" ></div>
  <!-- Fotorama -->
  <div class="fotorama"
  data-keyboard="true"
  data-nav="thumbs"
  data-width="500"
  data-ratio="500/367"
  data-max-width="100%">
  <?php
  $dir = opendir($photoFolder);
  $files = array();
  while ($files[] = readdir($dir));
    sort($files);
  closedir($dir);

  function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
  }

  foreach ($files as $file){
    if(endsWith($file, ".jpg")){
      echo "<img src=\"../house/$file\" >\n";
    }
  }
  ?>
  </div>

  <!-- time difference between two images -->
  <div id="diff">
  </div>

  <button onclick="removeImage()">remove</button>
  &nbsp; &nbsp; &nbsp;
  &nbsp; &nbsp; &nbsp;
  &nbsp; &nbsp; &nbsp;
  &nbsp; &nbsp; &nbsp;
  <button onclick="removeImageBeforeCurrent()">REMOVE EARLIER</button>

</body>
</html>