<?php


require_once "includes/_functions.php";

/**
 * Add body classes if certain regions have content.
 */
function default_drupal_theme_preprocess_html(&$vars) {
  if (!empty($vars['page']['featured'])) {
    $vars['classes_array'][] = 'featured';
  }

  if (!empty($vars['page']['triptych_first'])
    || !empty($vars['page']['triptych_middle'])
    || !empty($vars['page']['triptych_last'])) {
    $vars['classes_array'][] = 'triptych';
  }

  if (!empty($vars['page']['footer_firstcolumn'])
    || !empty($vars['page']['footer_secondcolumn'])
    || !empty($vars['page']['footer_thirdcolumn'])
    || !empty($vars['page']['footer_fourthcolumn'])) {
    $vars['classes_array'][] = 'footer-columns';
  }

  $vars['classes_array'][] = 'body_bgr forms_css';
}

/**
 * Override or insert variables into the page template for HTML output.
 */
function default_drupal_theme_process_html(&$vars) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_html_alter($vars);
  }
}

/**
 * Override or insert variables into the page template.
 */
function default_drupal_theme_process_page(&$vars) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_page_alter($vars);
  }
  // Always print the site name and slogan, but if they are toggled off, we'll
  // just hide them visually.
  $vars['hide_site_name']   = theme_get_setting('toggle_name') ? FALSE : TRUE;
  $vars['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
  if ($vars['hide_site_name']) {
    // If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
    $vars['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
  }
  if ($vars['hide_site_slogan']) {
    // If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
    $vars['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
  }
  // Since the title and the shortcut link are both block level elements,
  // positioning them next to each other is much simpler with a wrapper div.
  if (!empty($vars['title_suffix']['add_or_remove_shortcut']) && $vars['title']) {
    // Add a wrapper div using the title_prefix and title_suffix render elements.
    $vars['title_prefix']['shortcut_wrapper'] = array(
      '#markup' => '<div class="shortcut-wrapper clearfix">',
      '#weight' => 100,
    );
    $vars['title_suffix']['shortcut_wrapper'] = array(
      '#markup' => '</div>',
      '#weight' => -99,
    );
    // Make sure the shortcut link is the first item in title_suffix.
    $vars['title_suffix']['add_or_remove_shortcut']['#weight'] = -100;
  }

  //Front page
  // $vars['front_page'] = _default_drupal_theme_front_page();

  $vars['main_class'] = ' class="section section--main-region"';

  // $nodeTypeArray = array('region');
  // if (
  //   $vars['is_front'] 
  //   || isset($vars['node']) 
  //   && in_array($vars['node']->type, $nodeTypeArray)
  // ) {
  //   $vars['breadcrumb'] = '';
  //   $vars['title'] = '';
  // }

  //print_r($vars['theme_hook_suggestions']);


  // $termArray = array('page__membership');

  // foreach ($termArray as $value) {

  //   if (in_array($value, $vars['theme_hook_suggestions'])) {

  //     $vars['title'] = '';
  //   }
  // }

  

  //print_r($vars);

}

/**
 * Implements hook_preprocess_maintenance_page().
 */
function default_drupal_theme_preprocess_maintenance_page(&$vars) {
  // By default, site_name is set to Drupal if no db connection is available
  // or during site installation. Setting site_name to an empty string makes
  // the site and update pages look cleaner.
  // @see template_preprocess_maintenance_page
  if (!$vars['db_is_active']) {
    $vars['site_name'] = '';
  }
  drupal_add_css(drupal_get_path('theme', 'sand') . '/css/maintenance-page.css');
}

/**
 * Override or insert variables into the maintenance page template.
 */
function default_drupal_theme_process_maintenance_page(&$vars) {
  // Always print the site name and slogan, but if they are toggled off, we'll
  // just hide them visually.
  $vars['hide_site_name']   = theme_get_setting('toggle_name') ? FALSE : TRUE;
  $vars['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
  if ($vars['hide_site_name']) {
    // If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
    $vars['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
  }
  if ($vars['hide_site_slogan']) {
    // If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
    $vars['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
  }
}

/**
 * Override or insert variables into the node template.
 */
function default_drupal_theme_preprocess_node(&$vars) {

  $node = $vars['node'];

  // if ($vars['view_mode'] == 'full') {

  //   if (node_is_page($vars['node'])) {

  //     $vars['classes_array'][] = 'node-full';
  //   } 
  //   if ($vars['node']->nid == 66) {

  //     $vars['classes_array'][] = 'calculator';
  //   }
  //   if ($vars['node']->type == 'region') {
      
  //     $vars['content']['field_region_image'][0]['#image_style'] = 'v3_region_main_head';
      
  //     //print_r($vars);
  //   } 
  // }


  //print_r($vars['content']);

  // if (isset($vars['content']['field_image'][0]['#image_style'])) {

  //   $vars['content']['field_image'][0]['#image_style'] = 'big_pic_mobile';
  // }

  // if ($vars['view_mode'] == 'teaser') {
  //   $vars['title_attributes_array']['class'] = 'node__title';
  // }

  // $vars['socials_share'] = '';
  // //print '<!-- '; print_r($vars); print ' -->';
  // if ($vars['page']) {
    
  //   $vars['socials_share'] = _addSocialsShares($node);
  // }

  //print_r($vars);

  // if (isset($node->field_image_list)) {
    
  //   // $vars['field_image_list'][0]['test'] = 'test';
  //   // $vars['elements']['#node']->field_image_list['und'][0]['3test'] = '3test';
  //   // $node->field_image_list['und'][0]['test'] = 'test';
  //   // $node->field_image_list['und']['test1'] = 'test1';
  // }


}

function default_drupal_theme_process_node(&$vars) {

  //print_r($vars);
}

/**
 * Override or insert variables into the block template.
 */
function default_drupal_theme_preprocess_block(&$vars) {
  // In the header region visually hide block titles.
  if ($vars['block']->region == 'header') {
    $vars['title_attributes_array']['class'][] = 'element-invisible';
  }
  if ($vars['block']->region == 'drop_menu') {
    $vars['classes_array'][] = 'slidebar__item';
  }

  switch ($vars['block_html_id']) {

    // case 'block-search-form':
    //   $vars['classes_array'][] = 'default-view-search';
    //   break;

    default:
      # code...
      break;
  }
}

/**
 * Implements theme_menu_tree().
 */
function default_drupal_theme_menu_tree($vars) {
  return '<ul class="menu clearfix">' . $vars['tree'] . '</ul>';
}

/**
 * Implements theme_field__field_type().
 */
function default_drupal_theme_field__taxonomy_term_reference($vars) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$vars['label_hidden']) {
    $output .= '<h3 class="field-label">' . $vars['label'] . ': </h3>';
  }

  // Render the items.
  $output .= ($vars['element']['#label_display'] == 'inline') ? '<ul class="links inline">' : '<ul class="links">';
  foreach ($vars['items'] as $delta => $item) {
    $output .= '<li class="taxonomy-term-reference-' . $delta . '"' . $vars['item_attributes'][$delta] . '>' . drupal_render($item) . '</li>';
  }
  $output .= '</ul>';

  // Render the top-level DIV.
  $output = '<div class="' . $vars['classes'] . (!in_array('clearfix', $vars['classes_array']) ? ' clearfix' : '') . '"' . $vars['attributes'] .'>' . $output . '</div>';

  return $output;
}


/**
 * Preprocess the primary theme implementation for a view.
 */

function default_drupal_theme_preprocess_views_view(&$vars) {

  $modify = array(
    // '3v_front_main_news' => array(
    //   'display_id' => array(
    //     // 'block' => array(
    //     //   'extra_class' => 'sections-block',
    //     // ),
    //     'block_1' => array(
    //       'extra_class' => 'big-gallery'
    //     ),
    //     'block_2' => array(
    //       'extra_class' => 'block-plates',
    //       'row_prefix'  => '<div class="row">',
    //       'row_suffix'  => '</div>'
    //     ),
    //     'block_3' => array(
    //       'extra_class' => 'block-plates',
    //       'row_prefix'  => '<div class="row">',
    //       'row_suffix'  => '</div>'
    //     ),
    //   ),
    // ),
    

    // 'video_page_list' => array(
    //   'display_id' => 'page_1',
    //   'extra_class' => 'previews_page',
    // ),
  );

  $name = $vars['name'];
  $display_id = $vars['display_id'];

  if (in_array($name, array_keys($modify))) {
      // foreach ($vars as $key => $value) {
      //   print '<!-- ';print $key;print ' -->' . "\n";
      // }
    //print_r($vars['rows']);
    if (is_array($modify[$name]['display_id']) && in_array($display_id, array_keys($modify[$name]['display_id']))) {

      $vars['classes_array'][] = $modify[$name]['display_id'][$display_id]['extra_class'];
      //print_r($display_id);

    }
    elseif ($vars['display_id'] == $modify[$name]['display_id']) {
      $vars['classes_array'][] = $modify[$name]['extra_class'];
    }

    if (isset($modify[$name]['display_id'][$display_id]['row_prefix'])) {

      //print_r($modify[$name]['display_id'][$display_id]);
      $vars['rows'] = $modify[$name]['display_id'][$display_id]['row_prefix'] . $vars['rows'];
    }
    if (isset($modify[$name]['display_id'][$display_id]['row_suffix'])) {
      
      $vars['rows'] .= $modify[$name]['display_id'][$display_id]['row_suffix'];
    }
    //$vars['rows'] = $modify[$name]['display_id'][$display_id]['extra_class'] . $vars['rows'] . '</div>';
    // print_r($vars['rows']);
    
  }
}

function default_drupal_theme_preprocess_views_view_unformatted(&$vars) {
  $view    = $vars['view'];
  $name    = $view->name;
  $display = $view->current_display;
  $rows    = $vars['rows'];
  $style   = $view->style_plugin;
  $options = $style->options;

  $row_extra = array(

    // 'configurator' => array(

    //   'default' => array(

    //     'extra_class' => array('row-gr__item', 'row-gr__width-4', 'row-gr__md-width-6', 'row-gr__mb-width-12'),
    //     'prefix' => '<div class="configurator__item">',
    //     'suffix' => '</div>',
    //   ),

    // ),
  );

  $vars['classes_array'] = array();
  $vars['classes'] = array();
  $default_row_class = isset($options['default_row_class']) ? $options['default_row_class'] : FALSE;
  $row_class_special = isset($options['row_class_special']) ? $options['row_class_special'] : FALSE;
  // Set up striping values.
  $count = 0;
  $max = count($rows);
  foreach ($rows as $id => $row) {
    $count++;
    if ($default_row_class) {
      $vars['classes'][$id][] = 'views-row';
      $vars['classes'][$id][] = 'views-row-' . $count;
    }
    if ($row_class_special) {
      $vars['classes'][$id][] = 'views-row-' . ($count % 2 ? 'odd' : 'even');
      if ($count == 1) {
        $vars['classes'][$id][] = 'views-row-first';
      }
      if ($count == $max) {
        $vars['classes'][$id][] = 'views-row-last';
      }
    }

    if ($row_class = $view->style_plugin->get_row_class($id)) {
      $vars['classes'][$id][] = $row_class;
    }

    // Flatten the classes to a string for each row for the template file.
    $vars['classes_array'][$id] = isset($vars['classes'][$id]) ? implode(' ', $vars['classes'][$id]) : '';

    if (in_array($name, array_keys($row_extra)) && in_array($display, array_keys($row_extra[$name]))) {

      $row_extra_display = $row_extra[$name][$display];

      $vars['classes_array'][$id] .= ' ' . implode(' ', $row_extra_display['extra_class']);
      if (isset($row_extra_display['prefix'])) {
        $vars['rows'][$id] = $row_extra_display['prefix'] . $vars['rows'][$id];
      }
      if (isset($row_extra_display['suffix'])) {
        $vars['rows'][$id] .= $row_extra_display['suffix'];
      }
    }
  }
  // $vars['row'] = 'test';
  // print_r($rows);
}


function default_drupal_theme_form_alter(&$form, $form_state, $form_id) {

  switch ($form_id) {

    case 'search_block_form':

      $form['search_block_form']['#attributes']['placeholder'] = t('Site search');
      //print_r($form);

      break;

    // case 'simplenews_block_form_7901':

    //   //$form['search_block_form']['#attributes']['placeholder'] = t('Site search');
    //   //print_r($form);
    //   $form['mail']['#weight'] = -100;
    //   $form['action']['#weight'] = -99;

    //   break;

    /**
      *  register form theme
      *
      * @author Vladimir Khodakov <akma_2003@mail.ru>
      */


  //   case 'webform_client_form_76556':
      
  //     //print '<pre>'; print_r($form_state); print '</pre>';
  //     $form = _addPlaceholder($form, 'wrap');
  //     $form['submitted']['region']['#title_display'] = 'wrap';
  //     //$form['submitted']['region']['#title_display'] = 'wrap';
  //     foreach ($form['submitted'] as $key => $value) {
  //       if (is_array($form['submitted'][$key])) {
  //         //$form[$key]['#no_wrappers'] = true;
  //         $form['submitted'][$key]['#prefix'] = '<div class="row__item row__width-6 row__md-width-12">';
  //         $form['submitted'][$key]['#suffix'] = '</div>';
  //       }
  //     }

  //     //$form['#prefix'] = '<div class="entitytype-consultation-form"><div class="container">';
  //     //$form['#suffix'] = '</div></div>';

  //     // foreach ($form as $key => $value) {
  //     //   if (is_array($form[$key]) && $form[$key]['#type'] == 'container') {
  //     //     $form[$key]['#no_wrappers'] = true;
  //     //     $form[$key]['und']['#prefix'] = '';
  //     //     $form[$key]['und']['#suffix'] = '';

  //     //     if ($form[$key]['und']['#type'] == 'checkbox') {
  //     //       $form[$key]['und']['#title_display'] = 'after-check';
  //     //       //print '<!-- '; print_r($form[$key]); print ' -->';
  //     //     }
  //     //   }
  //     // }
  //     // $form['field_file']['und'][0]['#title_display'] = 'wrap';
  //     // $form['actions']['submit']['#attributes']['class'][] = 'btn';
  //     // //$form['actions']['submit']['#value'] = t('Submit');
  //     // $form['actions']['submit']['#value'] = t('Send');
      
  //     // break;
  }
}



function default_drupal_theme_field($vars) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$vars['label_hidden']) {
    $output .= '<div class="field-label"' . $vars['title_attributes'] . '>' . $vars['label'] . ':&nbsp;</div>';
  }

  $field_extra_class = '';
  $field_items_extra_class = '';

  if ($vars['element']['#field_name'] == 'field_image_list' && $vars['element']['#view_mode'] == 'full') {
    $field_extra_class = ' row-photo';
    $field_items_extra_class = ' row-photo__item row-photo__width-2 row-photo__w900-width-3 row-photo__w500-width-4 row-photo__w350-width-6 row-photo__w200-width-12';
  }

  // Render the items.
  $output .= '<div class="field-items' .  $field_extra_class .'"' . $vars['content_attributes'] . '>';
  //print_r($vars);
  foreach ($vars['items'] as $delta => $item) {
    $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even') . $field_items_extra_class;
    $output .= '<div class="' . $classes . '"' . $vars['item_attributes'][$delta] . '>' . drupal_render($item) . '</div>';
  }
  $output .= '</div>';

  // Render the top-level DIV.
  $output = '<div class="' . $vars['classes'] . '"' . $vars['attributes'] . '>' . $output . '</div>';

  return $output;
}


/**
 * Implements theme_pager().
 */
function default_drupal_theme_pager($vars) {
  $tags = $vars['tags'];
  $element = $vars['element'];
  $parameters = $vars['parameters'];
  $quantity = $vars['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = isset($pager_total[$element]) ? $pager_total[$element] : NULL;
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_max && $pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_first = theme('pager_first', array('text' => 1, 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => t('Previous'), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => t('Next'), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  if ( $pager_max ) $li_last = theme('pager_last', array('text' => $pager_max, 'element' => $element, 'parameters' => $parameters));

  if (isset($pager_total[$element]) && $pager_total[$element] > 1) {
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        if ($li_first) {
          $items[] = array(
            'class' => array('pager-first'),
            'data' => $li_first,
          );
        }
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '<span>…</span>',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('pager-current'),
            'data' => '<span><span>' . $i . '</span></span>',
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '<span>…</span>',
        );
        if ($li_last) {
          $items[] = array(
            'class' => array('pager-last'),
            'data' => $li_last,
          );
        }
      }
    }
    // End generation.

    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next'),
        'data' => $li_next,
      );
    }
    return '<h2 class="element-invisible">' . t('Pages') . '</h2>' . theme('item_list', array(
        'items' => $items,
        'attributes' => array('class' => array('pager')),
      ));
  }
}

/**
 * Implements theme_pager().
 */
function default_drupal_theme_pager_link($vars) {
  $text = $vars['text'];
  $page_new = $vars['page_new'];
  $element = $vars['element'];
  $parameters = $vars['parameters'];
  $attributes = $vars['attributes'];

  $page = isset($_GET['page']) ? $_GET['page'] : '';
  if ($new_page = implode(',', pager_load_array($page_new[$element], $element, explode(',', $page)))) {
    $parameters['page'] = $new_page;
  }

  $query = array();
  if (count($parameters)) {
    $query = drupal_get_query_parameters($parameters, array());
  }
  if ($query_pager = pager_get_query_parameters()) {
    $query = array_merge($query, $query_pager);
  }

  // Set each pager link title
  if (!isset($attributes['title'])) {
    static $titles = NULL;
    if (!isset($titles)) {
      $titles = array(
        t('<') => t('Go to first page'),
        t('‹ previous') => t('Go to previous page'),
        t('>') => t('Go to next page'),
        t('last »') => t('Go to last page'),
      );
    }
    if (isset($titles[$text])) {
      $attributes['title'] = $titles[$text];
    }
    elseif (is_numeric($text)) {
      $attributes['title'] = t('Go to page @number', array('@number' => $text));
    }
  }

  // @todo l() cannot be used here, since it adds an 'active' class based on the
  //   path only (which is always the current path for pager links). Apparently,
  //   none of the pager links is active at any time - but it should still be
  //   possible to use l() here.
  // @see http://drupal.org/node/1410574
  $attributes['href'] = url($_GET['q'], array('query' => $query));
  return '<a' . drupal_attributes($attributes) . '><span>' . check_plain($text) . '</span></a>';
}

function default_drupal_theme_item_list($vars) {
  $items = $vars ['items'];
  $title = $vars ['title'];
  $type = $vars ['type'];
  $attributes = $vars ['attributes'];

  // Only output the list container and title, if there are any list items.
  // Check to see whether the block title exists before adding a header.
  // Empty headers are not semantic and present accessibility challenges.
  $output = '<div class="item-list">';
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
            $attributes [$key] = $value;
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
        $attributes ['class'][] = 'first';
      }
      if ($i == $num_items) {
        $attributes ['class'][] = 'last';
      }
      $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>";
    }
    $output .= "</$type>";
  }
  $output .= '</div>';
  return $output;
}

/**
 * Returns HTML for the "last page" link in query pager.
 *
 * @param $vars
 *   An associative array containing:
 *   - text: The name (or image) of the link.
 *   - element: An optional integer to distinguish between multiple pagers on
 *     one page.
 *   - parameters: An associative array of query string parameters to append to
 *     the pager links.
 *
 * @ingroup themeable
 */
function default_drupal_theme_pager_last($vars) {
  $text = $vars['text'];
  $element = $vars['element'];
  $parameters = $vars['parameters'];
  global $pager_page_array, $pager_total;
  $output = '';

  // If we are anywhere but the last page
  if (isset($pager_page_array[$element]) && isset($pager_total[$element]) && $pager_page_array[$element] < ($pager_total[$element] - 1)) {
    $output = theme('pager_link', array('text' => $text, 'page_new' => pager_load_array($pager_total[$element] - 1, $element, $pager_page_array), 'element' => $element, 'parameters' => $parameters));
  }

  return $output;
}

/**
 * Returns HTML for the "next page" link in a query pager.
 *
 * @param $vars
 *   An associative array containing:
 *   - text: The name (or image) of the link.
 *   - element: An optional integer to distinguish between multiple pagers on
 *     one page.
 *   - interval: The number of pages to move forward when the link is clicked.
 *   - parameters: An associative array of query string parameters to append to
 *     the pager links.
 *
 * @ingroup themeable
 */
function default_drupal_theme_pager_next($vars) {
  $text = $vars['text'];
  $element = $vars['element'];
  $interval = $vars['interval'];
  $parameters = $vars['parameters'];
  global $pager_page_array, $pager_total;
  $output = '';

  // If we are anywhere but the last page
  if (isset($pager_page_array[$element]) && isset($pager_total[$element]) && $pager_page_array[$element] < ($pager_total[$element] - 1)) {
    $page_new = pager_load_array($pager_page_array[$element] + $interval, $element, $pager_page_array);
    // If the next page is the last page, mark the link as such.
    if ($page_new[$element] == ($pager_total[$element] - 1)) {
      $output = theme('pager_last', array('text' => $text, 'element' => $element, 'parameters' => $parameters));
    }
    // The next page is not the last page.
    else {
      $output = theme('pager_link', array('text' => $text, 'page_new' => $page_new, 'element' => $element, 'parameters' => $parameters));
    }
  }

  return $output;
}
