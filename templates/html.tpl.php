<?php
/**
 * html.tpl.php override for HTML% and to tidy things up
 */
?>
<!DOCTYPE html>
<html lang="<?php print $language->language; ?>">	
    <head>
    	<title><?php print $head_title; ?></title>  	
    	<?php print $head; ?>
    	<?php print $styles; ?> 
        <!--[if lte IE 8]>
        <script type="text/javascript" src="http://nts.sebastiaan.twokings.eu/sites/all/themes/nts/js/html5shiv.js"></script>
        <![endif]-->
    </head>

    <body class="<?php print $classes; ?>" <?php print $attributes;?>>
      	<?php print $page_top; ?>
      	<?php print $page; ?>
      	<?php print $page_bottom; ?>
        <?php print $scripts; ?> 
    </body>
</html>
