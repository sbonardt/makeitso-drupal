<?php
/**
 * views-view-unformatted.tpl override.
 * 
 *
 * IMPORTANT THEMING INFORMATION!:
 * views-view-unformatted.tpl override. Removes wrapping div per views result row. 
 * If class is user specified in views display row style, adds wrapping DIV with class to each row
 * If not, there is no wrapping DIV generated, as opposed to default template
 *
 *
 * @file
 * Default simple view template to display a list of rows.
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
 * @ingroup views_templates
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

