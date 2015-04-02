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
 * Alter html
 */
function makeitso_preprocess_html (&$variables) {
    // add old_ie.css stylesheet for old Internet Explorer browsers support
    drupal_add_css(drupal_get_path('theme', 'theme_base_overrides') . '/css/old_ie.css', array('group' => CSS_THEME,'browsers' => array('IE' => 'lte IE 8','!IE' => FALSE),'preprocess' => FALSE));
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