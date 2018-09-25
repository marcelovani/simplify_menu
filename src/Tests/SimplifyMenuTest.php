<?php

namespace Drupal\simplify_menu\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests Simplify Menu on contact pages.
 *
 * @group simplify_menu
 */
class SimplifyMenuTest extends WebTestBase {

  /**
   * Authenticated adminUser
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['simplify_menu', 'simplify_menu_test'];

  /**
   * Setup.
   */
  protected function setUp() {
    parent::setUp();

    $this->adminUser = $this->drupalCreateUser(array(
      'access administration pages',
    ), 'Admin User', TRUE);
  }

  /**
   * Test for Contact forms.
   */
  public function testTwigExtension() {
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('home');
    $element = $this->xpath('//span[@id="simplify-menu-test"]//a[@href="/"]');
    $this->assertTrue(count($element) === 1, 'The Main menu was rendered correctly');
    $element = $this->xpath('//span[@id="simplify-menu-test"]//a[@href="/admin"]');
    $this->assertTrue(count($element) === 1, 'The Admin menu was rendered correctly');

    $this->drupalLogout();
    $this->drupalGet('home');
    $element = $this->xpath('//span[@id="simplify-menu-test"]//a[@href="/"]');
    $this->assertTrue(count($element) === 1, 'The Main menu was rendered correctly');
    $element = $this->xpath('//span[@id="simplify-menu-test"]//a[@href="/admin"]');
    $this->assertTrue(count($element) === 0, 'The Admin menu is not visible');
  }

  /**
   * Check that an element exists in HTML markup.
   *
   * @param $xpath
   *   An XPath expression.
   * @param array $arguments
   *   (optional) An associative array of XPath replacement tokens to pass to
   *   DrupalWebTestCase::buildXPathQuery().
   * @param $message
   *   The message to display along with the assertion.
   * @param $group
   *   The type of assertion - examples are "Browser", "PHP".
   *
   * @return
   *   TRUE if the assertion succeeded, FALSE otherwise.
   */
  protected function assertElementByXPath($xpath, array $arguments = array(), $message, $group = 'Other') {
    $elements = $this->xpath($xpath, $arguments);
    return $this->assertTrue(!empty($elements[0]), $message, $group);
  }

}
