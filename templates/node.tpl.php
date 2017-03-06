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

  <div class="content clearfix"<?php print $content_attributes; ?>>
    <?php $no_created = array('region','authors','pro','webform'); ?>
    <?php if (!in_array($node->type, $no_created)) : ?>
      <div class="node__date"><?php print format_date($node->created, 'custom', 'l, j F, Y - H:i'); ?></div>
    <?php endif; ?>

    <?php
      print render($content);
    ?>

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

</div>
