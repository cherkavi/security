# security
* PHP application for displaying images ( and manage them ), 
which were uploaded to the FTP server from remote IP camera(s)

* If you have IP-cameras, just activate FTP notifications about moving in front of it ( them ).

Hardware:
  IP camera
Software server:
  just PHP hosting
Software client:
  HTML browser
  
How to install:
* Copy this app to the server and configure the settings into files:
* Change settings
- security.php, moveToHistory.php, remove.php, removeEarlier.php
<?php
    // path to the folder with uploaded images
    $photoFolder="/home/technik7/public_html/house/photo";
    // history folder, place for saving important images
    $photoFolderHistory="/home/technik7/public_html/house/photo_history";
    // photo extension
    $photoExtension=".jpg";
?>
- security.php, JS parameters

    /** length of time stamp of image */
    var timeStampLength=14;
    /** additional prefix of the name of the file*/
    var timeStampLengthMess=3;

    echo "<img src=\"../house/photo/$file\" >\n";
```
              +-------------+                                                                      
              | Object #1   |                                                                      
              |    +---+    |                                                                      
              |  X |Cam|    |  send images  +--------------+                                       
              |  XX| 1 +---------+          |           |-----+             +--------------+       
              |  X |   |    |    +---------->           +-----+             |          |------+    
              |    +---+    |               |  FTP Server  |                |          +------+    
              |             |  +------------>           +-----+             |dir-controller|       
              |    +---+    |  |            |           +-----+  +----------+          +------+    
              |  X |Cam+-------+  +--------->              |     |          |          |------+    
              |  XX| 2 |    |     |         +--------------+     |          +---+----------+       
              |  X |   |    |     |           use|               |              |                  
              |    +---+    |     |              |               |              |                  
              +-------------+     |              |               |              |                  
                                  |              |   +-----------+              |                  
                                  |              |   |  read/write              |                  
              +-------------+     |              |   |                     +----+--------------+X  
              |  Object #2  |     |              |   |                     |monitoring of dir    X 
              |    +---+    |     |         +----v---v-----+               |read file             X
              |  X |Cam|    |     |         |           |-----+            |remove file           +
              |  XX| 3 +----------+         |           +-----+            |read list of files    |
              |  X |   |    |               |File directory|               |remove files          |
              |    +---+    |               |           +-----+            |                      |
              |             |               |           |-----+            +----------------------+
              +-------------+               +--------------+                                       
                                                                                                   
                                                                                                   
                                                                                                   
                                                                                                   
                                           +--------------+                                        
                +----------------X         |           |-----+                                     
                |read files from  X        |           +-----+                                     
                |source folder     X       | file mover   |                                        
                |and move them     |       |           +-----+                                     
                |to destination    +-------+           |-----+                                     
                |folder            |       +--------------+                                        
                |                  |                                                               
                +------------------+                                                               
```
