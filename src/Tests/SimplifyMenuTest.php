<?php

namespace Drupal\simplify_menu\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests Simplify Menu on contact pages.
 *
 * @group simplify_menu
 */
class SimplifyMenuTest extends WebTestBase {

  protected $profile = 'standard';

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
  public static $modules = ['node', 'simplify_menu', 'simplify_menu_test'];

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
    // Test links with anonymous user.
    $this->drupalGet('node');
    $element = $this->xpath('//nav[@id="main"]//a[text()="Home" and @href="/"]');
    $this->assertTrue(count($element) === 1, 'The Main menu was rendered correctly');

    $element = $this->xpath('//nav[@id="account"]//a[text()="Inaccessible" and @href="/"]');
    $this->assertTrue(count($element) === 1, 'The Account menu is not visible');

    $element = $this->xpath('//nav[@id="account"]//a[text()="Log in" and @href="/user/login"]');
    $this->assertTrue(count($element) === 1, 'The Login menu is visible');

    $element = $this->xpath('//nav[@id="admin"]//a[text()="Inaccessible" and @href="/"]');
    $this->assertTrue(count($element) === 1, 'The Admin menu is not visible');

    // Test links with authenticated user.
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('node');
    $element = $this->xpath('//nav[@id="main"]//a[text()="Home" and @href="/"]');
    $this->assertTrue(count($element) === 1, 'The Main menu was rendered correctly');

    $element = $this->xpath('//nav[@id="account"]//a[text()="My account" and @href="/user"]');
    $this->assertTrue(count($element) === 1, 'The Account menu is visible');

    $element = $this->xpath('//nav[@id="account"]//a[text()="Log out" and @href="/user/logout"]');
    $this->assertTrue(count($element) === 1, 'The Login menu is visible');

    $element = $this->xpath('//nav[@id="admin"]//a[text()="Administration" and @href="/admin"]');
    $this->assertTrue(count($element) === 1, 'The Admin menu was rendered correctly');

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
