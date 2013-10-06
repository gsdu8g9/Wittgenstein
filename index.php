<?
/* 
===============================================

Wittgenstein 
 	index.php 

@author: Samuel Acuna 
@date: 10/2013 

Main interface for data requesting web app Wittgenstein

===============================================
*/

require_once('_incs/settings.php'); 
$count = mysqli_num_rows(mysqli_query($dblink,"SELECT id FROM data3")); 

?>
<!DOCTYPE html>
<html><head> 
 <title>Wittgenstein : Pulling data requests</title>
 <script src="_scrs/jquery.min.js"></script>
 <script src="_scrs/request.js"></script>
 <link rel="stylesheet" type="text/css" href="_stys/global.css">
</head><body> 

<div class="controls">
 <b>Wittgenstein</b> : Pulling data requests <a href="#" class="action" id="pause">&#xf04c;</a>
</div>
<br>
<div class="timer">
 Active
</div>
<br>
<div class="counter">
 Posts: <? echo $count; ?>
</div>
<br>
<div class="requests"></div>
 <input type="hidden" class="count" value="<? echo $count; ?>">

</body></html>