<?php

namespace Craft;

/**
 * Tag Manager Controller.
 *
 * Extends the default tag management options so we can edit and delete.
 *
 * @author    Bob Olde Hampsink <b.oldehampsink@itmundi.nl>
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   http://buildwithcraft.com/license Craft License Agreement
 *
 * @link      http://github.com/boboldehampsink
 */
class TagManagerController extends BaseController
{
    /**
     * Tag index.
     */
    public function actionTagIndex()
    {
        $variables['groups'] = craft()->tags->getAllTagGroups();

        $this->renderTemplate('tagmanager/_index', $variables);
    }

    /**
     * Edit a tag.
     *
     * @param array $variables
     *
     * @throws HttpException
     */
    public function actionEditTag(array $variables = array())
    {
        if (!empty($variables['groupHandle'])) {
            $variables['group'] = craft()->tags->getTagGroupByHandle($variables['groupHandle']);
        } elseif (!empty($variables['groupId'])) {
            $variables['group'] = craft()->tags->getTagGroupById($variables['groupId']);
        }
        if (empty($variables['group'])) {
            throw new HttpException(404);
        }
        // Now let's set up the actual tag
        if (empty($variables['tag'])) {
            if (!empty($variables['tagId'])) {
                $variables['tag'] = craft()->tags->getTagById($variables['tagId'], craft()->locale->id);
                if (!$variables['tag']) {
                    throw new HttpException(404);
                }
            } else {
                $variables['tag'] = new TagModel();
                $variables['tag']->groupId = $variables['group']->id;
            }
        }
        // Tabs
        $variables['tabs'] = array();
        foreach ($variables['group']->getFieldLayout()->getTabs() as $index => $tab) {
            // Do any of the fields on this tab have errors?
            $hasErrors = false;
            if ($variables['tag']->hasErrors()) {
                foreach ($tab->getFields() as $field) {
                    if ($variables['tag']->getErrors($field->getField()->handle)) {
                        $hasErrors = true;
                        break;
                    }
                }
            }
            $variables['tabs'][] = array(
                'label' => $tab->name,
                'url'   => '#tab'.($index + 1),
                'class' => ($hasErrors ? 'error' : null),
            );
        }
        if (!$variables['tag']->id) {
            $variables['title'] = Craft::t('Create a new tag');
        } else {
            $variables['title'] = $variables['tag']->title;
        }
        // Breadcrumbs
        $variables['crumbs'] = array(
            array('label' => Craft::t('Tag Manager'), 'url' => UrlHelper::getUrl('tagmanager')),
            array('label' => $variables['group']->name, 'url' => UrlHelper::getUrl('tagmanager')),
        );
        // Set the "Continue Editing" URL
        $variables['continueEditingUrl'] = 'tagmanager/'.$variables['group']->handle.'/{id}';
        // Render the template!
        $this->renderTemplate('tagmanager/_edit', $variables);
    }

    /**
     * Saves a tag.
     */
    public function actionSaveTag()
    {
        $this->requirePostRequest();
        $tagId = craft()->request->getPost('tagId');
        if ($tagId) {
            $tag = craft()->tags->getTagById($tagId, craft()->locale->id);
            if (!$tag) {
                throw new Exception(Craft::t('No tag exists with the ID “{id}”', array('id' => $tagId)));
            }
        } else {
            $tag = new TagModel();
        }
        // Set the tag attributes, defaulting to the existing values for whatever is missing from the post data
        $tag->groupId = craft()->request->getPost('groupId', $tag->groupId);
        $tag->getContent()->title = craft()->request->getPost('title', $tag->title);
        $tag->setContentFromPost('fields');
        if (craft()->tags->saveTag($tag)) {
            craft()->userSession->setNotice(Craft::t('Tag saved.'));
            $this->redirectToPostedUrl($tag);
        } else {
            craft()->userSession->setError(Craft::t('Couldn’t save tag.'));
            // Send the tag back to the template
            craft()->urlManager->setRouteVariables(array(
                'tag' => $tag,
            ));
        }
    }

    /**
     * Deletes a tag.
     */
    public function actionDeleteTag()
    {
        $this->requirePostRequest();
        $tagId = craft()->request->getRequiredPost('tagId');
        if (craft()->elements->deleteElementById($tagId)) {
            craft()->userSession->setNotice(Craft::t('Tag deleted.'));
            $this->redirectToPostedUrl();
        } else {
            craft()->userSession->setError(Craft::t('Couldn’t delete tag.'));
        }
    }
}
