<?php
/**
 * views-view-unformatted.tpl override. Removes wrapping div per row. 
 * If class is user specified in views display row style 
 * prints out wrapping div
 *
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 *
 *<?php if (!empty($title)): ?>
 *<h3><?php print $title; ?></h3>
 *<?php endif; ?>
 *<?php foreach ($rows as $id => $row): ?>
 *<div<?php if ($classes_array[$id]) { print ' class="' . $classes_array[$id] .'"';  } ?>>
 *<?php print $row; ?>
 *</div>
 *<?php endforeach; ?>
 *
 */
?>
<?php if (!empty($title)): ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>
<?php foreach ($rows as $id => $row): ?>
  <?php if (!empty($classes_array[$id])) { print '<div class="' . $classes_array[$id] .'">';  } ?>
     <?php print $row; ?>
  <?php if (!empty($classes_array[$id])) { print '</div>'; } ?>
<?php endforeach; ?>

