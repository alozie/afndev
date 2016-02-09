<?php

/**
 * @file
 * Custom Behat Step definitions.
 */

namespace Drupal;

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\DrupalExtension\Event\EntityEvent;
use Drupal\Component\Utility\Random;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;

/**
 * Class FeatureContext.
 *
 * Custom Behat step definitions.
 */
class FeatureContext extends RawDrupalContext implements SnippetAcceptingContext {

  protected $output;

  /**
   * Truncate the watchdog table so we can check for errors later.
   *
   * @BeforeSuite
   */
  public static function prepare($event) {

    db_truncate('watchdog')->execute();
  }

  /**
   * Check for PHP notices and errors.
   *
   * @AfterScenario
   */
  public function afterScenario($event) {

    $log = db_select('watchdog', 'w')
        ->fields('w')
        ->condition('w.type', 'php', '=')
        ->execute()
        ->fetchAll();
    if (!empty($log)) {
      foreach ($log as $error) {
        // Make the substitutions easier to read in the log.
        $error->variables = unserialize($error->variables);
        print_r($error);
      }
      throw new \Exception('PHP errors logged to watchdog in this scenario.');
    }
  }

  /**
   * Returns the current, relative path.
   *
   * Simply using Drupal's current_path() or $_GET['q'] does not work.
   *
   * @return string
   *   The current path.
   */
  public function getCurrentPath() {

    $url = $this->getSession()->getCurrentUrl();
    $parsed_url = parse_url($url);
    $path = trim($parsed_url['path'], '/');
    return $path;
  }

  /**
   * Returns node currently being viewed. Assumes /node/[nid] URL.
   *
   * Using path-based loaders, like menu_load_object(), will not work.
   *
   * @return object
   *   The currently viewed node.
   *
   * @throws Exceptionq
   */
  public function getNodeFromUrl() {

    $path = $this->getCurrentPath();
    $system_path = drupal_lookup_path('source', $path);
    if (!$system_path) {
      $system_path = $path;
    }
    $menu_item = menu_get_item($system_path);
    if ($menu_item['path'] == 'node/%') {
      $node = node_load($menu_item['original_map'][1]);
    }
    else {
      throw \Exception(sprintf("Node could not be loaded from URL '%s'", $path));
    }
    return $node;
  }

  /**
   * Returns the most recently created node.
   *
   * @return object
   *   The most recently created node.
   */
  public function getLastCreatedNode() {

    $node = end($this->nodes);
    return $node;
  }

  /**
   * Verify that a given Drupal region is visible.
   *
   * @Then /^I should not see the "([^"]*)" region$/
   */
  public function iShouldNotSeeTheRegion($region) {

    $session = $this->getSession();
    $region_object = $session->getPage()->find('region', $region);
    if ($region_object) {
      throw new \Exception(
            sprintf('The region "%s" was found on the page %s.', $region, $session->getCurrentUrl())
        );
    }
    return $region_object;
  }

  /**
   * Verifies that a given CSS selector exists.
   *
   * Note, this does not use business language and should not be used directly
   * in a test.
   *
   * @Then /^I should see the css selector "([^"]*)"$/
   * @Then /^I should see the CSS selector "([^"]*)"$/
   */
  public function iShouldSeeTheCssSelector($css_selector) {

    $element = $this->getSession()->getPage()->find("css", $css_selector);
    if (empty($element)) {
      throw new \Exception(sprintf("The page '%s' does not contain the css selector '%s'", $this->getSession()
        ->getCurrentUrl(), $css_selector));
    }
  }

  /**
   * Verifies that a given CSS selector does not exist.
   *
   * Note, this does not use business language and should not be used directly
   * in a test.
   *
   * @Then /^I should not see the css selector "([^"]*)"$/
   * @Then /^I should not see the CSS selector "([^"]*)"$/
   */
  public function iShouldNotSeeTheCssSelector($css_selector) {

    $element = $this->getSession()->getPage()->find("css", $css_selector);
    if (empty($element)) {
      throw new \Exception(sprintf("The page '%s' contains the css selector '%s'", $this->getSession()
        ->getCurrentUrl(), $css_selector));
    }
  }

  /**
   * Verifies that a given CSS selector exists in a given Drupal region.
   *
   * Note, this does not use business language and should not be used directly
   * in a test.
   *
   * @Then /^I should see the css selector "([^"]*)" in the "([^"]*)" region$/
   * @Then /^I should see the CSS selector "([^"]*)" in the "([^"]*)" region$/
   */
  public function iShouldSeeTheCssSelectorInTheRegion($css_selector, $region) {

    $region_object = $this->getRegion($region);
    $elements = $region_object->findAll('css', $css_selector);
    if (empty($elements)) {
      throw new \Exception(
            sprintf(
                'The css selector "%s" was not found in the "%s" region on the page %s',
                $css_selector,
                $region,
                $this->getSession()->getCurrentUrl()
            )
        );
    }
  }

  /**
   * Clicks a given CSS selector, even if it's not inherently clickable.
   *
   * Note, this does not use business language and should not be used directly
   * in a test.
   *
   * @When /^(?:|I )click the element with CSS selector "([^"]*)"$/
   * @When /^(?:|I )click the element with css selector "([^"]*)"$/
   */
  public function iClickTheElementWithCssSelector($css_selector) {

    $element = $this->getSession()->getPage()->find("css", $css_selector);
    if (empty($element)) {
      throw new \Exception(sprintf("The page '%s' does not contain the css selector '%s'", $this->getSession()
        ->getCurrentUrl(), $css_selector));
    }
    $element->click();
  }

  /**
   * Creates a new node with the given title.
   *
   * @Given /^I create a "([^"]*)" node with title "([^"]*)"$/
   */
  public function iCreateNodeWithTitle($type, $title) {

    $this->createNode($type, array('title' => $title));
  }

  /**
   * Creates a node.
   *
   * @Given /^I am viewing (?:a|an) "([^"]*)" node$/
   * @Given /^I create (?:a|an) "([^"]*)" node$/
   *
   * This overrides the parent createNode() method, allowing node properties
   * to be passes via $properties argument.
   *
   * @override
   */
  public function createNode($type, $properties = array()) {

    $node = (object) array(
      'title' => Random::string(25),
      'type' => $type,
      'uid' => 1,
    );
    if ($properties) {
      foreach ($properties as $key => $value) {
        $node->$key = $value;
      }
    }
    $this->dispatcher->dispatch('beforeNodeCreate', new EntityEvent($this, $node));
    $saved = $this->getDriver()->createNode($node);
    $this->dispatcher->dispatch('afterNodeCreate', new EntityEvent($this, $saved));
    $this->nodes[] = $saved;
    // Set internal page on the new node.
    $this->getSession()->visit($this->locatePath('/node/' . $saved->nid));
    return $saved;
  }

  /**
   * Populates required fields before node creation.
   *
   * @beforeNodeCreate
   */
  public function nodePreSave(EntityEvent $event) {

    $node = $event->getEntity();
    $node->status = 1;
    // Prevent bug caused by pathauto menu rebuild outside of Drupal context.
    // @see http://previousnext.com.au/blog/using-behat-and-drupaldriver-beware-pathauto
    $node->path = array('pathauto' => 0);
    if (module_exists('workbench_moderation')) {
      $node->workbench_moderation_state_new = 'published';
    }
  }

  /**
   * Clean up after node save.
   *
   * @afterNodeCreate
   */
  public function nodePostSave(EntityEvent $event) {

    $node = $event->getEntity();
    // By default, workbench_moderation delays calling node_save() on new
    // revisions until the PHP proc is being shutdown.
    if (module_exists('workbench_moderation') && !empty($node->workbench_moderation['published'])) {
      workbench_moderation_store($node);
    }
  }

  /**
   * Modify user entity before saving.
   *
   * @beforeUserCreate
   */
  public function userPreSave(EntityEvent $event) {

    $user = $event->getEntity();
    // Prevent bug caused by pathauto menu rebuild outside of Drupal context.
    // @see http://previousnext.com.au/blog/using-behat-and-drupaldriver-beware-pathauto
    $user->path = array('pathauto' => 0);
  }

  /**
   * Verifies the active Drupal theme.
   *
   * @Given /^I am viewing the "([^"]*)" theme$/
   */
  public function iAmViewingTheTheme($expected_theme) {

    global $theme;
    if ($theme !== $expected_theme) {
      throw new \Exception(
            sprintf("'%s' is not the active theme. '%s' is active instead.", $expected_theme, $theme)
        );
    }
  }

  /**
   * Verifies that a given <select> element contains a given <option>.
   *
   * @Then /^I should see a select element named "([^"]*)" containing "([^"]*)"
   *   as an option$/
   */
  public function iShouldSeeSelectElementNamedContainingAsAnOption($select, $option_value) {

    $select_element = $this->getSession()
        ->getPage()
        ->find('named', array('select', "\"{$select}\""));
    if (!$select_element) {
      throw new \Exception(sprintf("Did not find a <select> element '%s'.", $select));
    }
    $option_element = $select_element->find('named', array(
      'option',
      "\"{$option_value}\"",
    ));
    if (!$option_element) {
      throw new \Exception(
            sprintf("Did not find a <select> element '%s' with <option> '%s'.", $select, $option_value)
        );
    }
  }

  /**
   * Verifies that a given <select> element does not contain a given <option>.
   *
   * @Given /^I should see a select element named "([^"]*)" that does not
   *   contain "([^"]*)" as an option$/
   */
  public function iShouldSeeSelectElementNamedThatDoesNotContainAsAnOption($select, $option_value) {

    $select_element = $this->getSession()
        ->getPage()
        ->find('named', array('select', "\"{$select}\""));
    if (!$select_element) {
      throw new \Exception(sprintf("Did not find a <select> element '%s'.", $select));
    }
    $option_element = $select_element->find('named', array(
      'option',
      "\"{$option_value}\"",
    ));
    if ($option_element) {
      throw new \Exception(sprintf("Found <select> element '%s' with <option> '%s'.", $select, $option_value));
    }
  }

  /**
   * Clears a specific cache bin.
   *
   * @Given /^the "([^"]*)" cache bin has been cleared$/
   */
  public function theCacheBinHasBeenCleared($bin) {

    if ($bin == 'css' || $bin == 'js') {
      _drupal_flush_css_js();
      drupal_clear_css_cache();
      drupal_clear_js_cache();
    }
    elseif ($bin == 'block') {
      cache_clear_all(NULL, 'cache_block');
    }
    elseif ($bin == 'theme') {
      cache_clear_all('theme_registry', 'cache', TRUE);
    }
    else {
      cache_clear_all(NULL, $bin);
    }
  }

  /**
   * Fills in WYSIWYG editor with specified id.
   *
   * @Given /^(?:|I )fill in "(?P<text>[^"]*)" in WYSIWYG editor "([^"]*)"$/
   */
  public function iFillInInWysiwygEditor($text, $css_selector) {

    try {
      $element = $this->getSession()->getPage()->find('css', $css_selector);
      $id = $element->getAttribute('id');
      $this->getSession()->switchToIFrame($id);
    }
    catch (Exception $e) {
      throw new \Exception(sprintf("No iframe with id '%s' found on the page '%s'.", $id, $this->getSession()
        ->getCurrentUrl()));
    }
    $this->getSession()
        ->executeScript("document.body.innerHTML = '<p>" . $text . "</p>'");
    $this->getSession()->switchToIFrame();
  }

  /**
   * Selects a set of <select> elements with given <option>s.
   *
   * @When /^I select the following <fields> with <values>$/
   */
  public function iSelectTheFollowingFieldsWithValues(TableNode $table) {

    $multiple = TRUE;
    $table = $table->getHash();
    foreach ($table as $key => $value) {
      $select = $this->getSession()
            ->getPage()
            ->findField($table[$key]['fields']);
      if (empty($select)) {
        throw new Exception(
              "The page does not have the field with id|name|label|value '" . $table[$key]['fields'] . "'"
          );
      }
      // If multiple is always true we get "value cannot be an array" error for
      // single select fields.
      $multiple = $select->getAttribute('multiple') ? TRUE : FALSE;
      $this->getSession()
            ->getPage()
            ->selectFieldOption($table[$key]['fields'], $table[$key]['values'], $multiple);
    }
  }

  /**
   * Pauses for a given number of seconds.
   *
   * @Given /^I wait (\d+) seconds$/
   */
  public function iWaitSeconds($seconds) {

    sleep($seconds);
  }

  /**
   * Wait for AJAX to finish.
   *
   * @Given /^I wait for AJAX to finish$/
   */
  public function iWaitForAjaxToFinish() {

    $this->getSession()
        ->wait(5000, '(typeof(jQuery)=="undefined" || (0 === jQuery.active && 0 === jQuery(\':animated\').length))');
  }

  /**
   * Determines whether the current browser is Internet Explorer.
   *
   * @return int
   *   If the browser is IE, the IE version will be returned. Otherwise, -1.
   */
  public function getIeVersion() {

    $ie_version = $this->getSession()->evaluateScript('
      var rv = -1; // Return value assumes failure.
      if (navigator.appName == "Microsoft Internet Explorer")
      {
        var ua = navigator.userAgent;
        var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
        if (re.exec(ua) != null)
          rv = parseFloat( RegExp.$1 );
      }
      return rv;
    ');
    return $ie_version;
  }

  /**
   * For javascript enabled scenarios, always wait for AJAX before clicking.
   *
   * @BeforeStep @javascript
   */
  public function beforeStep($event) {

    $text = $event->getStep()->getText();
    if (preg_match('/(follow|press|click|submit)/i', $text)) {
      $this->iWaitForAjaxToFinish();
    }
  }

  /**
   * For javascript enabled scenarios, always wait for AJAX after clicking.
   *
   * @AfterStep @javascript
   */
  public function afterStep($event) {

    $text = $event->getStep()->getText();
    if (preg_match('/(follow|press|click|submit)/i', $text)) {
      $this->iWaitForAjaxToFinish();
    }
  }

}
