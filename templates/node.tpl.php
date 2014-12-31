<?php
/**
 * default node.tpl for all nodes. HTML 5 <article> element. Adjust here, 
 * use teaser display in views in view displays.
 *
 * Displays teaser if requested. Teaser prints node title and summary field from 
 * body field or generated summary field from body text based on $max_length 
 * (defaults to 300). id $node prints all base fields
 *
 * Assumes you have a field_image in node.tpl
 */

if ($teaser): ?>

<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
    <?php
// We hide the comments and links now so that we can render them later.
    hide($content['links']);
    hide($content['field_tags']);
    hide($content['field_image']);
    ?>

    <h1<?php print $title_attributes; ?>>
        <a href="<?php print $node_url; ?>"><?php print $title; ?></a>
    </h1>          
    <?php 
    if ($node->body['und'][0]['safe_summary']) {
        print '<p>' . strip_tags(render($node->body['und'][0]['safe_summary'])) . '</p>'; ;
    }
    else {
        $text = $node->body['und'][0]['value'];
        $max_length = 300;

        if (strlen($text) > $max_length) {    
            $text = strip_tags($text);            
            $text = substr($text, 0, $max_length);
            $text = substr($text, 0, (strrpos($text, ' ')));                    
            print '<p>' . $text . '</p>';
        }
        else {
            print '<p>' . $text . '</p>';
        }                   
    }                      
    ?>
</article>

<?php else: ?>

    <article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
        <?php
// We hide the comments and links now so that we can render them later.
        hide($content['links']);
        hide($content['field_tags']);
        hide($content['field_image']);
        ?>
        <?php print render($title_prefix); ?>
        <h1 id="page-title" <?php print $title_attributes; ?>><?php print $title; ?></h1>
        <?php print render($title_suffix); ?>

        <?php if ($display_submitted): ?>
            <div class="meta submitted">
                <?php print $user_picture; ?>
                <?php print $submitted; ?>
            </div>
        <?php endif; ?>

        <?php
// do some stuff for the gallery. If one image no galleria, else yes galleria, responsive and all
        $image = $node->field_image;  
        if (isset($image['und'][0]['filename'])) {  
            $imgcount = count($node->field_image['und']);
            $firstimg = $node->field_image['und'][0];
        }
        else {
            $imgcount = 0;  
        }
// if there's 1 image
        if ($imgcount == 1) {
            $image_alt = $node->field_image['und'][0]['alt'];
            $image_description = $node->field_image['und'][0]['title'];
            print '<figure><img class="" src="' . image_style_url('large', $firstimg['uri']) . '" alt="' . $image_alt . '"  title="' . $image_description . '" /><figcaption>' . $image_description . '</figcaption></figure>';
        }
// if there's more than 1 image
        else if ($imgcount > 1) {   
            print '<div class="gallery-wrapper">' . render($content['field_image']) . '</div>';
        }

        print render($content);
        ?>

        <?php
// Remove the "add new comment" link on the teaser page or if the comment
// form is being displayed on the same page.
        if ($teaser || !empty($content['comments']['comment_form'])) {
            unset($content['links']['comment']['#links']['comment-add']);
        }
// Only display the wrapper div if there are links.
        $links = render($content['links']);
        if ($links):
            ?>
        <div class="link-wrapper">
            <?php print $links; ?>
        </div>
    <?php endif; ?>

    <?php print render($content['comments']); ?>

</article>

<?php endif; ?>