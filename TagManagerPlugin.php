<?php

namespace Craft;

/**
 * Tag Manager Plugin.
 *
 * Gives tags a nice element interface for quick 'n easy editing.
 *
 * @author    Bob Olde Hampsink <b.oldehampsink@itmundi.nl>
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   http://buildwithcraft.com/license Craft License Agreement
 *
 * @link      http://github.com/boboldehampsink
 */
class TagManagerPlugin extends BasePlugin
{
    /**
     * Get plugin name.
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Tag Manager');
    }

    /**
     * Get plugin version.
     *
     * @return string
     */
    public function getVersion()
    {
        return '0.2.0';
    }

    /**
     * Get plugin developer.
     *
     * @return string
     */
    public function getDeveloper()
    {
        return 'Bob Olde Hampsink';
    }

    /**
     * Get plugin developer url.
     *
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'http://www.itmundi.nl';
    }

    /**
     * Has cp section.
     *
     * @return bool
     */
    public function hasCpSection()
    {
        return true;
    }

    /**
     * Register routes for Control Panel.
     *
     * @return array
     */
    public function registerCpRoutes()
    {
        return array(
            'tagmanager'                                          => array('action' => 'tagManager/tagIndex'),
            'tagmanager/(?P<groupHandle>{handle})/new'            => array('action' => 'tagManager/editTag'),
            'tagmanager/(?P<groupHandle>{handle})/(?P<tagId>\d+)' => array('action' => 'tagManager/editTag'),
        );
    }
}
