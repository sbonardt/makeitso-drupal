<?php
/**
 * Block.tpl override
 * Strips unneeded html containers/wrappers, adds class(es) from block_class module: https://www.drupal.org/project/block_class
 * See block.tpl.php on api.drupal.org for original
 */
?>
<div class="block<?php if (!empty($block->css_class)) { print ' ' . $block->css_class; } ?>">
	<?php if ($block->subject): ?>
	   <h2<?php print $title_attributes; ?>><?php print $block->subject; ?></h2>
	<?php endif;?>
	<?php print $content ?>
</div>
