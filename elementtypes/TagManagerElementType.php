<?php

namespace Craft;

/**
 * Tag Manager Element Type.
 *
 * Extends the default Tag Element Type so we can populate a slightly different model.
 *
 * @author    Bob Olde Hampsink <b.oldehampsink@itmundi.nl>
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   http://buildwithcraft.com/license Craft License Agreement
 *
 * @link      http://github.com/boboldehampsink
 */
class TagManagerElementType extends TagElementType
{
    /**
     * @inheritDoc IElementType::populateElementModel()
     *
     * @param array $row
     *
     * @return array
     */
    public function populateElementModel($row)
    {
        return TagManagerModel::populateModel($row);
    }
}
