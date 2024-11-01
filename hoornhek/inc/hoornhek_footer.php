<?php

include('check_role.php');


?>
<?php if(empty($autRol)) {
   ?>
<footer class="hoornhek_footer">
   <div>   
   Welkom op de Hoornhek applicatie</div>

</footer>
   <?php
} else {
   ?>
<footer class="hoornhek_footer">
   <div>   
   <?php echo "Je rol: ".$autRol. " " . "Je inlognaam: ". $inlognaam ?> 
   <?php echo "Je werkt op: " . $locatie ?> </div>

</footer>

   <?php
} ?> 

<!-- </div> -->
</body>

</html>
