<?php
/**
 * maintentenance-page.tpl.php
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
    	<div class="logo"><a href="<?php print $front_page ?>"><?php print $site_name; ?></a></div>

    	<div id="main" class="inner">
        	<div id="content">
        		<h1><?php print $title ?></h1>
        		<?php print render ($page['content']) ?>
        		<p><?php print $content; ?></p>
            </div>
       	</div>
        <?php print $scripts; ?> 
    </body>
</html>
