<?php

namespace Craft;

/**
 * Tag Manager Model.
 *
 * Adds a CP edit url to the Tag Model.
 *
 * @author    Bob Olde Hampsink <b.oldehampsink@itmundi.nl>
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   http://buildwithcraft.com/license Craft License Agreement
 *
 * @link      http://github.com/boboldehampsink
 */
class TagManagerModel extends TagModel
{
    /**
     * @inheritDoc BaseElementModel::getCpEditUrl()
     *
     * @return string|false
     */
    public function getCpEditUrl()
    {
        $group = $this->getGroup();

        if ($group) {
            return UrlHelper::getCpUrl('tagmanager/'.$group->handle.'/'.$this->id);
        }
    }
}
