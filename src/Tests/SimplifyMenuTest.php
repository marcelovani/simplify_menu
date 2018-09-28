<?php

namespace Drupal\simplify_menu\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\menu_link_content\Entity\MenuLinkContent;

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
  public static $modules = ['node', 'simplify_menu', 'simplify_menu_test'];

  /**
   * Setup.
   */
  protected function setUp() {
    parent::setUp();

    $this->adminUser = $this->drupalCreateUser(array(
      'access administration pages',
    ), 'Admin User', TRUE);

    // Create menu links.
    $menu_link = MenuLinkContent::create([
      'title' => 'Home',
      'link' => ['uri' => '<front>'],
      'menu_name' => 'main',
      'expanded' => TRUE,
    ]);
    $menu_link->save();
  }

  /**
   * Test for Contact forms.
   */
  public function testTwigExtension() {
    // Create content type.
    $node_type = NodeType::create([
      'type' => 'page',
      'name' => 'Basic page',
    ]);
    $node_type->save();

    // Create page.
    $page = Node::create([
      'type' => 'page',
      'title' => 'Test page',
    ]);
    $page->save();

    $this->drupalLogin($this->adminUser);
    $this->drupalGet('node/' . $page->id());
    $element = $this->xpath('//span[@id="simplify-menu-test"]//a[@href="/"]');
    $this->assertTrue(count($element) === 1, 'The Main menu was rendered correctly');
    $element = $this->xpath('//span[@id="simplify-menu-test"]//a[@href="/admin"]');
    $this->assertTrue(count($element) === 1, 'The Admin menu was rendered correctly');

    $this->drupalLogout();
    $this->drupalGet('node/' . $page->id());
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
