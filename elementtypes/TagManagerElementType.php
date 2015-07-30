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

    // Element index methods
    // -------------------------------------------------------------------------

    /**
     * @inheritDoc IElementType::getAvailableActions()
     *
     * @param string|null $source
     *
     * @return array|null
     */
    public function getAvailableActions($source = null)
    {
        $actions = array();

        // Edit
        $editAction = craft()->elements->getAction('Edit');
        $editAction->setParams(array(
            'label' => Craft::t('Edit tag'),
        ));
        $actions[] = $editAction;

        // Delete
        $deleteAction = craft()->elements->getAction('Delete');
        $deleteAction->setParams(array(
            'confirmationMessage' => Craft::t('Are you sure you want to delete the selected tags?'),
            'successMessage'      => Craft::t('Tags deleted.'),
        ));
        $actions[] = $deleteAction;

        // Allow plugins to add additional actions
        $allPluginActions = craft()->plugins->call('addTagManagerActions', array($source), true);
        foreach ($allPluginActions as $pluginActions) {
            $actions = array_merge($actions, $pluginActions);
        }

        return $actions;
    }

    /**
     * @inheritDoc IElementType::defineSortableAttributes()
     *
     * @retrun array
     */
    public function defineSortableAttributes()
    {
        $attributes = array(
            'title' => Craft::t('Title'),
        );

        // Allow plugins to modify the attributes
        craft()->plugins->call('modifyTagManagerSortableAttributes', array(&$attributes));

        return $attributes;
    }

    /**
     * @inheritDoc IElementType::defineTableAttributes()
     *
     * @param string|null $source
     *
     * @return array
     */
    public function defineTableAttributes($source = null)
    {
        $attributes = array(
            'title' => Craft::t('Title'),
        );

        // Allow plugins to modify the attributes
        craft()->plugins->call('modifyTagManagerTableAttributes', array(&$attributes, $source));

        return $attributes;
    }

    /**
     * @inheritDoc IElementType::getTableAttributeHtml()
     *
     * @param BaseElementModel $element
     * @param string           $attribute
     *
     * @return mixed|null|string
     */
    public function getTableAttributeHtml(BaseElementModel $element, $attribute)
    {
        // Give plugins a chance to set this
        $pluginAttributeHtml = craft()->plugins->callFirst('getTagManagerTableAttributeHtml', array($element, $attribute), true);

        // Return the plugins version, if there is one
        if ($pluginAttributeHtml !== null) {
            return $pluginAttributeHtml;
        }

        // Default to the base table html
        return parent::getTableAttributeHtml($element, $attribute);
    }
}
