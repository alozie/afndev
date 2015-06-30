<?php if (isset($content['name'])): ?>
<h2><?php print render($content['name']); ?></h2>
<?php endif; ?>

<?php if (isset($content['image'])): ?>
<div class="image-wrapper"><?php print render($content['image']); ?></div>
<?php endif; ?>

<?php if (isset($content['description'])): ?>
<p><?php print render($content['description']); ?></p>
<?php endif; ?>

<?php if (isset($content['link'])): ?>
<div class="link-wrapper"><?php print render($content['link']); ?></div>
<?php endif; ?>
