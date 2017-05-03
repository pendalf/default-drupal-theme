<?php




/**
 * Implements theme_pager().
 */
function default_drupal_theme_pager($vars) {
  $tags = $vars['tags'];
  $element = $vars['element'];
  $parameters = $vars['parameters'];
  //$quantity = $vars['quantity'];
  $quantity = 3;
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
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
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
  $li_previous = theme('pager_previous', array('text' => t('←'), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => t('→'), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => $pager_max, 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous', 'pager-item'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        if ($li_first) {
          $items[] = array(
            'class' => array('pager-first', 'pager-item'),
            'data' => $li_first,
          );
        }
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
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
          'data' => '…',
        );
        if ($li_last) {
          $items[] = array(
            'class' => array('pager-last', 'pager-item'),
            'data' => $li_last,
          );
        }
      }
    }
    // End generation.

    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next', 'pager-item'),
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


/*
 *  function theme_form_element_label
 */

function default_drupal_theme_form_element_label($vars) {
  $element = $vars['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // If title and required marker are both empty, output no label.
  if ((!isset($element['#title']) || $element['#title'] === '') && empty($element['#required'])) {
    return '';
  }

  // If the element is required, a required marker is appended to the label.
  $required = !empty($element['#required']) ? theme('form_required_marker', array('element' => $element)) : '';

  $title = filter_xss_admin($element['#title']);

  $wrapper_attributes = isset($element['#wrapper_attributes']) ? $element['#wrapper_attributes'] : array('class' => array());
  // Style the label as class option to display inline with the element.
  if ($element['#title_display'] == 'after') {
    $attributes['class'] = 'option';
  }
  // Show label only to screen readers to avoid disruption in visual flows.
  elseif ($element['#title_display'] == 'invisible') {
    $attributes['class'] = 'element-invisible';
  }

  if (!empty($element['#id'])) {
    $attributes['for'] = $element['#id'];
  }

  $label_prefix = '<div class="form-label-wrap">';
  $label_suffix = '</div>';
    $label_prefix = '';
    $label_suffix = '';

  $label_tag = 'label';
  $label_end = '</' . $label_tag . '>';
  

  if (isset($vars['label_as_wrap'])) {
    $label_tag = 'div';
    $label_end = '';
    $label_prefix = '';
    $label_suffix = '';

    if (isset($element['#field_name']) && $element['#field_name'] == 'field_file') {

      $title = '<span>' . $element['#title'] . '</span>';
    }
    else {

      $title = '<span></span>';
    }

    $required = '';

    if (!empty($element['#required'])) {
      $attributes['class'][] = 'required';
    }
    
  }
  if (isset($vars['#after-check'])) {
    $label_end = '';
    $required = '';
    //$title = '<span></span>';
    if (!empty($element['#required'])) {
      $attributes['class'][] = 'required';
    }    
  }

  if ( isset($element['#type']) && $element['#type'] == 'textfield' && strpos($element['#children'], ' error') !== false) {

    $attributes['class'][] = 'has-error';
  }

  // The leading whitespace helps visually separate fields from inline labels.
  return ' ' . $label_prefix . '<' . $label_tag . drupal_attributes($attributes) . '>' . $t('!title !required', array('!title' => $title, '!required' => $required)) . $label_end . $label_suffix . "\n";
}



/*
 *  function theme_container
 */

function default_drupal_theme_container($vars) {
  $element = $vars['element'];
  // print '<!-- '; print_r($element); print ' -->';
  // Ensure #attributes is set.
  $element += array('#attributes' => array());

  // Special handling for form elements.
  if (isset($element['#array_parents'])) {
    // Assign an html ID.
    if (!isset($element['#attributes']['id'])) {
      $element['#attributes']['id'] = $element['#id'];
    }
    // Add the 'form-wrapper' class.
    $element['#attributes']['class'][] = 'form-wrapper';
  }

  if (isset($element['#no_wrappers'])) {
    return $element['#children'];
  }

  return '<div' . drupal_attributes($element['#attributes']) . '>' . $element['#children'] . '</div>';
}


/*
 *  function theme_form_element
 */

function default_drupal_theme_form_element($vars) {
  $element = &$vars['element'];

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array(
    '#title_display' => 'before',
  );

  // Add element #id for #type 'item'.
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  // Add element's #type and #name as class to aid with JS/CSS selectors.
  $attributes['class'] = array('form-item');
  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }
  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
  }
  // Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  
  if (!empty($element['#type']) && $element['#type'] == 'checkbox' && strpos($element['#children'], ' error') !== false) {
    $attributes['class'][] = 'row has-error';
  }
  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';


  switch ($element['#title_display']) {
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $vars);
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $vars) . "\n";
      break;

    case 'after-check':
      $vars['#after-check'] = true;
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $vars) . "\n";
      if (!empty($element['#description'])) {
        $output .= '<div class="add-link">' . $element['#description'] . "</div>\n";
        unset($element['#description']);
      }
      if ($element['#required']) {
        $output .= '<span class="red-point"></span>';
      }
      $output .= '</label>';
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'wrap':

      // $fields = array('textfield', 'email');

      // if (in_array($element['#type'], $fields) && strpos($element['#children'], ' error') !== false) {

      //   $element['#children'] = str_replace ( ' error', ' error-1', $element['#children'] );
      // }
      $vars['label_as_wrap'] = true;
      $output  = theme('form_element_label', $vars);
      $output .= ' ' . $element['#children'] . "\n";
      if (!empty($element['#description'])) {
        $output .= '<div class="description">' . $element['#description'] . "</div>\n";
      }
      $output .= '</div>';
      return $output;
      break;
  }

  if (!empty($element['#description'])) {
    $output .= '<div class="description">' . $element['#description'] . "</div>\n";
  }

  $output .= "</div>\n";


  return $output;
}

function default_drupal_theme_webform_element($vars) {
  // Ensure defaults.
  $vars['element'] += array(
    '#title_display' => 'before',
  );

  $element = $vars['element'];

  // All elements using this for display only are given the "display" type.
  if (isset($element['#format']) && $element['#format'] == 'html') {
    $type = 'display';
  }
  else {
    $type = (isset($element['#type']) && !in_array($element['#type'], array('markup', 'textfield', 'webform_email', 'webform_number'))) ? $element['#type'] : $element['#webform_component']['type'];
  }

  // Convert the parents array into a string, excluding the "submitted" wrapper.
  $nested_level = $element['#parents'][0] == 'submitted' ? 1 : 0;
  $parents = str_replace('_', '-', implode('--', array_slice($element['#parents'], $nested_level)));

  $wrapper_attributes = isset($element['#wrapper_attributes']) ? $element['#wrapper_attributes'] : array('class' => array());
  $wrapper_classes = array(
    'form-item',
    'webform-component',
    'webform-component-' . $type,
  );
  if (isset($element['#title_display']) && strcmp($element['#title_display'], 'inline') === 0) {
    $wrapper_classes[] = 'webform-container-inline';
  }
  $wrapper_attributes['class'] = array_merge($wrapper_classes, $wrapper_attributes['class']);
  $wrapper_attributes['id'] = 'webform-component-' . $parents;
  $output = '<div ' . drupal_attributes($wrapper_attributes) . '>' . "\n";

  // If #title_display is none, set it to invisible instead - none only used if
  // we have no title at all to use.
  if ($element['#title_display'] == 'none') {
    $vars['element']['#title_display'] = 'invisible';
    $element['#title_display'] = 'invisible';
    if (empty($element['#attributes']['title']) && !empty($element['#title'])) {
      $element['#attributes']['title'] = $element['#title'];
    }
  }
  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . _webform_filter_xss($element['#field_prefix']) . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . _webform_filter_xss($element['#field_suffix']) . '</span>' : '';

  switch ($element['#title_display']) {
    case 'inline':
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $vars);
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $vars) . "\n";
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'wrap':

      // $fields = array('textfield', 'email');

      // if (in_array($element['#type'], $fields) && strpos($element['#children'], ' error') !== false) {

      //   $element['#children'] = str_replace ( ' error', ' error-1', $element['#children'] );
      // }
      $vars['label_as_wrap'] = true;
      $output  = theme('form_element_label', $vars);
      $output .= ' ' . $element['#children'] . "\n";
      if (!empty($element['#description'])) {
        $output .= '<div class="description">' . $element['#description'] . "</div>\n";
      }
      $output .= '</div>';
      return $output;
      break;
  }

  if (!empty($element['#description'])) {
    $output .= ' <div class="description">' . $element['#description'] . "</div>\n";
  }

  $output .= "</div>\n";

  return $output;
}


/**
 * Override of theme_breadcrumb().
 */
function default_drupal_theme_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb">' . implode(' / ', $breadcrumb) . '</div>';
    return $output;
  }
}



function _addSocialsShares($node) {

  $link    = check_url(url( $_GET['q'], array('absolute'=>TRUE) ));
  $output  = '<div class="socials-share">';
  $output .= ' <div class="socials-share-wrap fb-like">';
  $output .= '  <a href="" data-href="' .$link. '" data-send="false" data-layout="button_count" data-show-faces="false">0</a>';
  $output .= ' </div>';
  $output .= ' <div class="socials-share-wrap vk-like">';
  $output .= '  <a href="" data-href="' .$link. '">0</a>';
  $output .= ' </div>';
  // $output .= ' <div class="socials-share-wrap ok-like">';
  // $output .= '  <a href="" data-href="' .$link. '">0</a>';
  // $output .= ' </div>';
  // $output .= ' <div class="socials-share-wrap mailru-like">';
  // //$output .= '  <a target="_blank" class="mrc__plugin_uber_like_button" href="http://connect.mail.ru/share" data-mrc-config="{\'cm\' : \'1\', \'sz\' : \'20\', \'st\' : \'1\', \'tp\' : \'mm\'}">Нравится</a><script src="http://cdn.connect.mail.ru/js/loader.js" type="text/javascript" charset="UTF-8"></script>';
  // $output .= '  <a href="" data-href="' .$link. '">0</a>';
  // $output .= ' </div>';
  // $output .= ' <div class="socials-share-wrap gp-like">';
  // $output .= '  <a href="" data-href="' .$link. '">0</a>';
  // $output .= ' </div>';
  $output .= ' <div class="socials-share-wrap tw-like">';
  //$output .= '  <a href="https://twitter.com/share" class="twitter-share-button" data-url="'. $link . '" data-lang="ru">Твитнуть</a>';
  //$output .= '  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>';
  $output .= ' <a href="http://twitter.com/share" class="tweet" data-url="' . $link . '" data-via="" data-text="' . $node->title . '"><span class="count">0</span></a>';
  //$output .= ' <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
  $output .= ' </div>';
  $output .= '</div>';

  return $output;
}

/**
  *  Add placeholder to form
  *
  * @author Vladimir Khodakov <akma_2003@mail.ru>
  */

function _addPlaceholder($form, $display = 'invisible'){
  if (count($form)) {

    foreach ($form as $key => $value) {
      if(is_array($value)){
        $form[$key] = _addPlaceholder($value, $display);
      }
      if(is_array($value) && !empty($value['#type'])) {
        switch ($value['#type']) {
          case 'textfield':
          case 'textarea':
          case 'email':
          case 'password':
          case 'webform_email':
            //print $value['#type'];
            $form[$key]['#attributes']['placeholder'] = $form[$key]['#title'];
            $form[$key]['#title_display'] = $display;
            if(!empty($value['#required']) && $display == 'invisible'){
              $form[$key]['#attributes']['placeholder'] .= '*';
            }
            break;
          
          default:
            # code...
            break;
        }
        
      }
    }
  }
  return $form;
}

