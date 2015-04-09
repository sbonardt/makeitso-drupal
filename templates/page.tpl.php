<?php
/**
 * page.tpl override.
 *
 * 
 * IMPORTANT THEMING INFORMATION!:
 * page.tpl HTML 5 and ARIA in mind and in combination with node.tpl
 * Generic setup with all Drupal page elements in logic and most common order.
 * Adds an ASIDE region from makeitso.info
 * Uses a generic 'inner-wrap' classed DIV for all items that are direct siblings
 * of body instead of a main wrapping DIV wrapping all these items for esthetic
 * front-end code purposes.... brrraughh.... grey poupon....
 *
 *
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 * least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 * or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 * when linking to the front page. This includes the language domain or
 * prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 * in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 * in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 * site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 * the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 * modules, intended to be displayed in front of the main title tag that
 * appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 * modules, intended to be displayed after the main title tag that appears in
 * the template.
 * - $messages: HTML for status and error messages. Should be displayed
 * prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 * (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 * menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 * associated with the page, and the node ID is the second argument
 * in the page's path (e.g. node/12345 and node/12345/revisions, but not
 * comment/reply/12345).
 *
 * Regions:
 * See makeitso.info for the new regions used in this theme.
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['aside']: Items for the aside.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
 *
 * @ingroup themeable
 */ 
?>

<header role="banner">
    <div class="inner-wrap">
        <a class="skipcontent" href="#content">Ga naar de inhoud</a>
        <a class="logo" href="<?php print $front_page ?>"><img src="<?php print $logo; ?>" alt ="<?php print $site_name; ?>" /><span class="site-name"><?php print $site_name; ?></span></a>

        <?php 
            // print $searchblock from template.php    
            print $searchblock; 
        ?>
        <?php if ($secondary_menu): ?>
        <?php 
            print theme('links__system_secondary_menu', 
            array(
                'links' => $secondary_menu, 
                'attributes' => array(  'class' => array('menu secondary-menu meta-menu'))
                ));
        ?>
        <?php endif; ?> 

        <?php if ($page['header']): ?>
            <?php print render($page['header']); ?>
        <?php endif; ?>
    </div>
</header>

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

        <a id="content" tabindex="0"></a>
        <?php if (!isset($node)): ?>
        <div class="main-content">
            <?php if ($title): ?>
            <h1 class="title" id="page-title"><?php print $title; ?></h1>
            <?php endif; ?>
        <?php endif; ?>
        <?php print render($title_suffix); ?>
        <?php print render($page['content_top']); ?>
        <?php print render($page['content']); ?>
        <?php print render($page['content_bottom']); ?>
        <?php if (!isset($node)): ?>
        </div>
        <?php endif; ?>

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