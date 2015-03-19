<?php
/**
 * block--system--main.tpl override
 * 
 *
 * IMPORTANT THEMING INFORMATION!:
 * Template for system main content block. Removes block class wrapping div
 * see block.tpl.php on api.drupal.org for original
 *
 */
?>
<?php if ($block->subject): ?>
    <h2<?php print $title_attributes; ?>><?php print $block->subject; ?></h2>
<?php endif;?>
<?php print $content ?>
