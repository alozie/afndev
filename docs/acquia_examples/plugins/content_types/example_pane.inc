<?php
/**
 * Example Plugin.
 * @var array
 */
$plugin = array(
  'single' => TRUE,
  'title' => t('Example Pane'),
  'description' => t('Example Panel'),
  'category' => t('Examples'),
);

/**
 * Implements hook_PLUGIN_content_type_render().
 *
 * Ctools requires us to return a block.
 *
 * @see ctools_content_render()
 */
function acquia_examples_example_pane_content_type_render($subtype, $conf, $panel_args, $context) {
  $block = new stdClass();

  $block->content = array(
    '#theme' => 'acquia_examples_example_pane',
  );

  if (isset($conf['description'])) {
    $block->content['description'] = array(
      'type' => 'markup',
      '#markup' => $conf['description'],
    );
  }

  if (isset($conf['name'])) {
    $block->content['name'] = array(
      'type' => 'markup',
      '#markup' => $conf['name'],
    );
  }

  if (isset($conf['link_text']) && isset($conf['link_url'])) {
    $block->content['link'] = array(
      '#theme' => 'link',
      '#text' => $conf['link_text'],
      '#path' => $conf['link_url'],
      '#options' => array(
        'attributes' => array(),
        'html' => FALSE,
      ),
    );
  }

  if (isset($conf['image'])) {
    $file = file_load($conf['image']);

    if ($file) {
      $block->content['image'] = array(
        '#theme' => 'image_style',
        '#path' => $file->uri,
        '#style_name' => 'medium',
        '#attributes' => array(
          'alt' => '',
          'title' => 'Example Image',
        ),
      );
    }
  }

  return $block;
}

/**
 * Returns an edit form for a entity.
 */
function acquia_examples_example_pane_content_type_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];

  if (isset($conf['image'])) {
    $file = file_load($conf['image']);
    if ($file) {
      $form['image_preview'] = array(
        '#type' => 'item',
        '#title'  => t('Current Image'),
        '#markup' => theme_image(array(
          'path'  => file_create_url($file->uri),
          'alt'   => '',
          'attributes' => array(),
        )),
      );
      $form['image_fid'] = array(
        '#type' => 'hidden',
        '#default_value' => $file->fid,
      );
      $form['image_delete'] = array(
        '#type' => 'checkbox',
        '#title' => 'Delete Image',
        '#default_value' => $file->fid,
      );
    }
  }

  $form['image'] = array(
    '#title' => t('Image'),
    '#type' => 'file',
    '#default_value' => isset($conf['image']) ? $conf['image'] : '',
    '#attributes' => array('default_image_path' => ''),
  );

  $form['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
    '#default_value' => isset($conf['name']) ? $conf['name'] : '',
    '#required' => TRUE,
  );

  $form['description'] = array(
    '#title' => t('Description'),
    '#type' => 'textarea',
    '#default_value' => isset($conf['description']) ? $conf['description'] : '',
    '#rows' => 10,
    '#cols' => 60,
    '#resizable' => TRUE,
  );

  $form['link_text'] = array(
    '#type' => 'textfield',
    '#title' => t('Link Text'),
    '#default_value' => isset($conf['link_text']) ? $conf['link_text'] : '',
    '#required' => TRUE,
  );

  $form['link_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Link URL'),
    '#default_value' => isset($conf['link_url']) ? $conf['link_url'] : '',
    '#required' => TRUE,
  );

  $form['#validate'][] = 'acquia_examples_example_pane_content_type_edit_form_validate';

  return $form;
}

/**
 * Save magazine pane.
 */
function acquia_examples_example_pane_content_type_edit_form_submit(&$form, &$form_state) {
  global $user;

  // Upload and save image.
  $file = file_save_upload('image', array());

  if ($file) {
    // Save to file system.
    $file = file_move($file, 'public://example-images');

    // Save to database.
    $file->status = FILE_STATUS_PERMANENT;
    file_save($file);

    // Save to panel.
    $form_state['conf']['image'] = $file->fid;
  }

  // Delete Image.
  if (isset($form_state['values']['image_delete']) && $form_state['values']['image_delete'] == TRUE) {
    $file = file_load($form_state['values']['image_fid']);
    file_delete($file);
    $form_state['conf']['image'] = '';
  }

  if (isset($form_state['values']['name'])) {
    $form_state['conf']['name'] = $form_state['values']['name'];
  }

  if (isset($form_state['values']['description'])) {
    $form_state['conf']['description'] = $form_state['values']['description'];
  }

  if (isset($form_state['values']['link_text'])) {
    $form_state['conf']['link_text'] = $form_state['values']['link_text'];
  }

  if (isset($form_state['values']['link_url'])) {
    $form_state['conf']['link_url'] = $form_state['values']['link_url'];
  }
}

/**
 * Form validation.
 * @param $form
 * @param $form_state
 */
function acquia_examples_example_pane_content_type_edit_form_validate(&$form, &$form_state) {

}

/**
 * Implements hook_PLUGIN_content_type_admin_title().
 *
 * Returns the administrative title for a type.
 */
function acquia_examples_example_pane_content_type_admin_title($entity_type, $conf, $contexts) {
  return t('Example Pane: @name', array('@name' => $conf['name']));
}
