<?php
global $user;
$is_admin = ($user->uid == 1);
?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php print render($title_prefix); ?>
  <?php if (!$page): ?>
    <h2<?php print $title_attributes; ?>>
      <a href="<?php print $node_url; ?>"><?php print $title; ?></a>
    </h2>
  <?php endif; ?>
  <?php print render($title_suffix); ?>

  <?php if ($display_submitted): ?>
    <div class="meta submitted">
      <?php print $user_picture; ?>
      <?php print $submitted; ?>
    </div>
  <?php endif; ?>

  <?php if (isset($node->field_region_image)) {
    print '<div class="node-region__main-img">';
    print '<div class="block__title block__title--page"><h1>' . $title . '</h1></div>';
    print render($content['field_region_image']);
    hide($content['field_region_image']);
    print '</div>';
  }?>

  <div class="content clearfix"<?php print $content_attributes; ?>>
    <?php $no_created = array('region','authors','pro','webform'); ?>
    <?php if (!in_array($node->type, $no_created)) : ?>
      <div class="node__date"><?php print format_date($node->created, 'custom', 'l, j F, Y - H:i'); ?></div>
    <?php endif; ?>

    <?php
        if ( isset($node->field_author['und']) && isset($node->field_author['und'][0]['nid']) ) {
            $author_nid = $node->field_author['und'][0]['nid'];            
            print '<div class="node__author">' . views_embed_view('authors', 'block_1', $author_nid ) . '</div>';
             
            hide($content['field_main_image']);
        }
    ?>

   <?php if( isset($node->field_video) && isset($node->field_video['und']) && count($node->field_video['und']) ) :?>                    
   <?php foreach ($node->field_video['und'] as $v): ?>
       <?php if(ctype_digit($v['value'])):?>
	   <object><embed width="640" height="360" align="middle" flashvars="stats=http://www.1tv.ru/addclick/" allowscriptaccess="always" swliveconnect="true"  wmode="opaque" allowfullscreen="true" quality="high" bgcolor="white" name="videoportal" id="videoportal" src="http://www.1tv.ru/newsvideo/<?php echo $v['value'] ?>" type="application/x-shockwave-flash"/></object>
       <?php else:?>
          <iframe width="624" height="354" src="http://www.youtube.com/embed/<?php echo $v['value'] ?>?rel=0&wmode=opaque" frameborder="0" allowfullscreen></iframe>
       <?php endif?>
   <?php endforeach ?>
   <?php endif ?>


    <?php

     if ($node->type == 'pro' && $page) {
        print views_embed_view('projects','page_1', $node->nid);
     }
     else {

      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      hide($content['field_video']);
      hide($content['field_fotorama']);

      if ($page) {
        if ($node->type == 'photo') {
          $content['field_updown'][0]['#markup'] = $socials_share . $content['field_updown'][0]['#markup'];
        }
        else {
          if (!in_array($node->type, array('region','quiz','authors','pro'))) {
            if ($node->type == 'translations') {
              $block = module_invoke('shoutbox', 'block_view', 'shoutbox');
              $content['body'][0]['#markup'] .= render($block['content']);
            }

            if (isset($content['body'][0]['#markup'])) {
              if (isset($content['field_fotorama'])) {
                 $content['body'][0]['#markup'] .= render($content['field_fotorama']);
              }
              $content['body'][0]['#markup'] .= $socials_share;
              if (isset($content['field_updown'])) {
                $field_updown_content = $content['field_updown'];
                $content['body'][0]['#markup'] .= render($field_updown_content);
                hide($content['field_updown']);
              }
            }
            else {
              if ($is_admin) { 
                //print 'no body suffix';
              } 
            }
          }
        }
      }

      print render($content);

      }
    ?>

  <?php if ( $node->type == 'authors' && $page ): ?>
     <?php
         $q = views_get_view('opinions');
         $q->set_arguments(array($node->nid));
         $q->build();
         $q->query->limit = 0;
         $q->execute();
     ?>

    <h4>Публикации:</h4>
    <ul>
    <?php if ( count($q->result) ) :?>
    <?php foreach ($q->result as $i => $result): ?>
     <li class="clear">
       <span><?php echo format_date($result->node_created, 'custom', 'd.m.Y') ?></span>
       <a href="<?php echo url('node/' . $result->nid) ?>"><?php echo $result->node_title ?></a>
       </li>
     <?php endforeach ?>
     <?php endif ?>
    </ul>        
  <?php endif; ?>

  <?php
   if (in_array($node->type, array('quiz','authors'))) {
     print $socials_share;
   }
  ?>


  </div>

  <?php
    // Remove the "Add new comment" link on the teaser page or if the comment
    // form is being displayed on the same page.
    if ($teaser || !empty($content['comments']['comment_form'])) {
      unset($content['links']['comment']['#links']['comment-add']);
    }
    // Only display the wrapper div if there are links.
    $links = render($content['links']);
    if ($links && $node->type != 'region'):
  ?>
    <div class="link-wrapper">
      <?php print $links; ?>
    </div>
  <?php endif; ?>

  <?php if ($node->type != 'region'):?>
  	<p><a href="https://telegram.me/mger2020ru" target="_new" class="read-us-telegram">Читайте нас в telegram</a></p>
  <?php endif; ?>


</div>
