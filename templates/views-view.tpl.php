<?php
/**
 * views-view.tpl override.
 * Strips unneeded html containers/wrappers, adds class from view config in div if class is set
 * See views-view.tpl.php on api.drupal.org for original
 */
?>
<?php if ($css_class) {
    print '<div class="' . $css_class . '">';
}
?>
<?php print render($title_prefix); ?>
<?php if ($title): ?>
    <?php print $title; ?>
<?php endif; ?>
<?php print render($title_suffix); ?>
<?php if ($header): ?>
    <?php print $header; ?>
<?php endif; ?>

<?php if ($exposed): ?>
    <div class="view-filters">
        <?php print $exposed; ?>
    </div>
<?php endif; ?>

<?php if ($attachment_before): ?>
    <?php print $attachment_before; ?>
<?php endif; ?>

<?php if ($rows): ?>
    <?php print $rows; ?>
<?php elseif ($empty): ?>
    <div class="view-empty">
        <?php print $empty; ?>
    </div>
<?php endif; ?>

<?php if ($pager): ?>
    <div class="pager-wrap">  
        <?php print $pager; ?>
    </div>
<?php endif; ?>

<?php if ($attachment_after): ?>
    <?php print $attachment_after; ?>
<?php endif; ?>

<?php if ($more): ?>
    <?php print $more; ?>
<?php endif; ?>

<?php if ($footer): ?>
    <?php print $footer; ?>
<?php endif; ?>

<?php if ($feed_icon): ?>
    <div class="feed-icon">
        <?php print $feed_icon; ?>
    </div>
<?php endif; ?>

<?php if ($css_class) {
    print '</div>';
}
?>