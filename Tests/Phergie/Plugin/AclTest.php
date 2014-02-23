<?php
/**
 * Phergie
 *
 * PHP version 5
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://phergie.org/license
 *
 * @category  Phergie
 * @package   Phergie_Tests
 * @author    Phergie Development Team <team@phergie.org>
 * @copyright 2008-2010 Phergie Development Team (http://phergie.org)
 * @license   http://phergie.org/license New BSD License
 * @link      http://pear.phergie.org/package/Phergie_Tests
 */

/**
 * Unit test suite for Phergie_Plugin_Acl.
 *
 * @category Phergie
 * @package  Phergie_Tests
 * @author   Phergie Development Team <team@phergie.org>
 * @license  http://phergie.org/license New BSD License
 * @link     http://pear.phergie.org/package/Phergie_Tests
 */
class Phergie_Plugin_AclTest extends Phergie_Plugin_TestCase
{

    /**
     * Blacklist sample entry.
     *
     * @var array
     */
    public $blacklist = array('127.0.0.1' => array(
                                'badnick!user@host' => array(
                                    'plugins' => array(
                                        'TerryChay'
                                    ),
                                    'methods' => array(
                                        'method'
                                    )
                                )
                            )
    );
    
    /**
     * Whitelist sample entry.
     *
     * @var array
     */
    public $whitelist = array('127.0.0.1' => array(
                        'goodnick!user@host' => array(
                            'plugins' => array(
                                'TerryChay'
                            ),
                            'methods' => array(
                                'method'
                            )
                        )
                    )
        );

    /**
     * Tests to make sure the UserInfo plugin is called in onLoad().
     * 
     * @return void
     */
    public function testOnLoadCallsForUserInfoPlugin()
    {
        $this->assertRequiresPlugin('UserInfo');

        $this->plugin->onLoad();
    }

    /**
     * Tests that the plugin unloads itself if both acl.whitelist or acl.blacklist
     * are not configured.
     *
     * @return void
     */
    public function testOnLoadRemovesPluginIfNoListsConfigured()
    {
        $this->assertRemovesPlugin($this->plugin);

        $this->plugin->onLoad();
    }

    /**
     * Data provider for testApplyRules
     *
     * @return array
     */
    public function applyRulesProvider()
    {
        return array(
                    array('plugins' => array('Foo', 'Bar')),
                    array('methods' => array('baz', 'bat'))
        );
    }

    /**
     * Tests applyRules method.  The iterator should be called once for each
     * data set.
     *
     * @dataProvider applyRulesProvider
     * @return void
     */
    public function testApplyRules($array)
    {
        $iterator=$this->getMock('Phergie_Plugin_Iterator', array('addPluginFilter', 'accept'));
        $iterator->expects($this->once())
                 ->method('addPluginFilter')
                 ->with($this->equalTo($array));

        $this->plugin->applyRules($iterator, $array);
    }
}
