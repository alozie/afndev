<?php if ($page['navigation']): ?>
  <?php print render($page['navigation']); ?>
<?php endif; ?>

<?php if ($page['featured']): ?>
  <?php print render($page['featured']); ?>
<?php endif; ?>

<?php if ($page['highlighted']): ?>
  <?php print render($page['highlighted']); ?>
<?php endif; ?>

<?php if ($page['sidebar_first']): ?>
  <?php print render($page['sidebar_first']); ?>
<?php endif; ?>

<?php if ($page['content']): ?>
  <?php print render($page['content']); ?>
<?php endif; ?>

<?php if ($page['sidebar_second']): ?>
  <?php print render($page['sidebar_second']); ?>
<?php endif; ?>

<?php if ($page['footer']): ?>
  <?php print render($page['footer']); ?>
<?php endif; ?>
