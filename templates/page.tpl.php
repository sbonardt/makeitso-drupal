<?php
/**
 * page.tpl override with HTML 5 and ARIA in mind
 */
?>

<header role="banner">
    <a class="skipcontent" href="#content">Ga naar de inhoud</a>
    <a class="logo" href="<?php print $front_page ?>"><img src="<?php print $logo; ?>" alt ="<?php print $site_name; ?>" /><span><?php print $site_name; ?></span></a>

    <?php if ($page['header']): ?>
        <?php print render($page['header']); ?>
    <?php endif; ?>

    <?php 
        // print $searchblock from template.php    
        print '<div class="search">' . $searchblock . '</div>'; 
    ?>

    <?php if ($secondary_menu): ?>
    <?php 
        print theme('links__system_secondary_menu', 
        array(
            'links' => $secondary_menu, 
            'attributes' => array(  'class' => array('menu secondary-menu'))
            ));
    ?>
    <?php endif; ?> 
    <nav role="navigation">
        <?php if ($main_menu): ?>
        <?php
        print theme('links__menu_main_menu', 
        array(
            'links' => $main_menu, 
            'attributes' => array(  'class' => array('menu main-menu'))
            )); 
        ?>
        <?php endif; ?> 
    </nav>
</header>

<main role="main">  
    <div class="inner-wrap">
        <?php if ($breadcrumb) { print $breadcrumb; } ?>
        <?php if ($messages) { print $messages; } ?>
        <?php if ($tabs) { print render($tabs); } ?>
        <?php print render($tabs2); ?>

        <?php if ($page['highlighted']): ?>
        <div class="highlighted">
        <?php print render($page['highlighted']); ?>
        </div>
        <?php endif; ?>

        <a id="content" name="content" tabindex="0"></a>
        <div class="main-content">
        <?php if (!isset($node)): ?> 
            <?php if ($title): ?>
            <h1 class="title" id="page-title"><?php print $title; ?></h1>
            <?php endif; ?>
        <?php endif; ?>
        <?php print render($title_suffix); ?>
        <?php print render($page['content_top']); ?>
        <?php print render($page['content']); ?>
        <?php print render($page['content_bottom']); ?>
        </div>

        <?php if ($page['aside']): ?>
        <aside>
            <?php print render($page['aside']) ?>
        </aside>
        <?php endif; ?>
    </div>
</main>

<footer>
    <div class="inner-wrap">
        <?php if ($page['footer']): ?>
            <?php print render($page['footer']) ?>
        <?php endif; ?>
    </div>
</footer>