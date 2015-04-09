<?php
/**
 * Remove and add elements in head
 */
function makeitso_html_head_alter(&$head_elements) {
    // remove generator meta tag
    unset($head_elements['system_meta_generator']);  
    // add following meta tags
    $head_elements['viewporttag'] = array('#type' => 'html_tag','#tag' => 'meta','#attributes' => array('name' => 'viewport','content' => 'width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=10.0',),'#weight' => 0,);    
    $head_elements['x-ua-compat'] = array('#type' => 'html_tag','#tag' => 'meta','#attributes' => array('http_equiv' => 'X-UA-Compatible','content' => 'IE=edge,chrome=1',),'#weight' => 0,);  
    $head_elements['apple_icon'] = array('#type' => 'html_tag','#tag' => 'link','#attributes' => array('href' => base_path() . path_to_theme() .'/apple-touch-icon.png','rel' => 'apple-touch-icon-precomposed',),'#weight' => 2,);
    $head_elements['gen_favicon'] = array('#type' => 'html_tag','#tag' => 'link','#attributes' => array('href' => base_path() . path_to_theme() .'/favicon.ico','rel' => 'shortcut icon',),'#weight' => 1,);
    $head_elements['system_meta_content_type']['#attributes'] = array('charset' => 'utf-8');
    drupal_add_html_head($head_elements, 'gen_favicon');
    drupal_add_html_head($head_elements, 'apple_icon');
    drupal_add_html_head($head_elements, 'viewporttag');  
    drupal_add_html_head($head_elements, 'x-ua-compat'); 

    // do not allow IOS/Android to alter telephone links automagically, by setting this meta tag
    $meta_ios_tel_links = array('#type' => 'html_tag','#tag' => 'meta','#attributes' => array('name' => 'format-detection','content' =>  'telephone=no'));
    drupal_add_html_head($meta_ios_tel_links, 'meta_ios_tel_links');
}

/**
 * Alter html(.tpl.php)
 */
function makeitso_preprocess_html (&$variables) {
    // add old_ie.css stylesheet for old Internet Explorer browsers support
    drupal_add_css(drupal_get_path('theme', 'makeitso') . '/css/old_ie.css', array('group' => CSS_THEME,'browsers' => array('IE' => 'lte IE 8','!IE' => FALSE),'preprocess' => FALSE));
    // add aside class to body if aside region is present. Used for theming purposes
    if (!empty($variables['page']['aside'])) {$variables['classes_array'][] = 'aside';}
}

/**
* Unset drupal base theming if needed for custom front-end theme
*/
function makeitso_css_alter(&$css) { 
    //unset($css[drupal_get_path('module','system').'/system.base.css']);
    unset($css[drupal_get_path('module','system').'/system.menus.css']);  
    unset($css[drupal_get_path('module','system').'/system.theme.css']); 
}

/**
 * Override or insert variables into the page template.
 */
function makeitso_preprocess_page(&$variables) {
// page template suggestions base on node type: page--type--interview, page--type--cursus, etc.
    if (!empty($variables['node'])) {
        $variables['theme_hook_suggestions'][] = 'page__type__' . $variables['node']->type;
    }

// move secondary tabs into a separate variable.
    $variables['tabs2'] = array(
        '#theme' => 'menu_local_tasks',
        '#secondary' => $variables['tabs']['#secondary'],
        );
    unset($variables['tabs']['#secondary']);

// template suggestions to target specific taxonomy voc. pages based on voc.id page--vocabulary--id
    if (arg(0) == 'taxonomy' && arg(1) == 'term' && is_numeric(arg(2))) {
        $tid = arg(2);
        $vid = db_query("SELECT vid FROM {taxonomy_term_data} WHERE tid = :tid", array(':tid' => $tid))->fetchField();
        $variables['theme_hook_suggestions'][] = 'page__vocabulary__' . $vid;
    }   

// put the search block form in a separate variable '$searchblock' for use in page.tpl
    $customsearchblock = module_invoke('search','block_view','search'); 
    $customsearchrendered_block = render($customsearchblock);
    $variables['searchblock'] = $customsearchrendered_block;
}

/**
 * Override or insert variables into the node template.
 */
function makeitso_preprocess_node(&$variables) {
// add node-full class to node
    if ($variables['elements']['#view_mode'] == 'full') {
        $variables['classes_array'][] = 'main-content node-full';
    }
// add node-teaser class to node if teaser
    if ($variables['elements']['#view_mode'] == 'teaser') {
        $variables['classes_array'][] = 'node-teaser';
    }
}

/**
 * Override or insert variables into the block template.
 */
function makeitso_preprocess_block(&$variables) {
    $block = $variables['block'];
// add a simple edit link to blocks for priviliged users
    if (user_access('administer blocks') && $block->module == "block"){
        $variables['content'] = l(t('edit'), 'admin/structure/block/manage/block/' . $block->delta, array('attributes'=>array('class'=>'block_edit', 'title'=>'edit '.$block->subject.' block'), 'query'=>drupal_get_destination())). $variables['content'];
    }
}

/**
 * Remove wrapping div excess from drupal fields-module fields
 */
function makeitso_field(&$variables) {
$output = '';
  // Render the label, if it's not hidden.
  if (!$variables ['label_hidden']) {
    $output .= '<div class="field-label"' . $variables ['title_attributes'] . '>' . $variables ['label'] . ':&nbsp;</div>';
  }
  foreach ($variables ['items'] as $delta => $item) {
    $output .=  drupal_render($item);
  }
  return $output;
}

/**
 * Return a themed breadcrumb trail. Simple and conscise, links in a div
 */
function makeitso_breadcrumb(&$variables) {
    $breadcrumb = $variables['breadcrumb'];
    if (!empty($breadcrumb)) {
        $output = '<div class="breadcrumb">' . implode('', $breadcrumb) . '</div>';
        return $output;
    }
}

/**
 * Remove width and height fro the equasion. For responsiveness'ess
 */
function makeitso_image(&$variables) {
    $attributes = $variables['attributes'];
    $attributes['src'] = file_create_url($variables['path']);
    // remove attributes height and width
    // foreach (array('width', 'height', 'alt', 'title') as $key) {
    foreach (array('alt', 'title') as $key) {
        if (isset($variables[$key])) {
            $attributes[$key] = $variables[$key];
        }
    }
    return '<img' . drupal_attributes($attributes) . ' />';
}

/**
 * Remove wrapping div around item-list
 */
function makeitso_item_list(&$variables) {
    $items = $variables['items'];
    $title = $variables['title'];
    $type = $variables['type'];
    $attributes = $variables['attributes'];
    $output = '';
    if (isset($title) && $title !== '') {
        $output .= '<h3>' . $title . '</h3>';
    }
    if (!empty($items)) {
        $output .= "<$type" . drupal_attributes($attributes) . '>';
        $num_items = count($items);
        $i = 0;
        foreach ($items as $item) {
            $attributes = array();
            $children = array();
            $data = '';
            $i++;
            if (is_array($item)) {
                foreach ($item as $key => $value) {
                    if ($key == 'data') {
                        $data = $value;
                    }
                    elseif ($key == 'children') {
                        $children = $value;
                    }
                    else {
                        $attributes[$key] = $value;
                    }
                }
            }
            else {
                $data = $item;
            }
            if (count($children) > 0) {
            // Render nested list.
                $data .= theme_item_list(array('items' => $children, 'title' => NULL, 'type' => $type, 'attributes' => $attributes));
            }
            if ($i == 1) {
                $attributes['class'][] = 'first';
            }
            if ($i == $num_items) {
                $attributes['class'][] = 'last';
            }
            $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>\n";
        }
        $output .= "</$type>";
    }
    $output .= '';
    return $output;
}