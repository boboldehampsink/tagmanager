<?php

namespace Craft;

class TagManager_MergeElementAction extends BaseElementAction
{
    public function getName()
    {
        return Craft::t('Merge Tags');
    }

    public function getTriggerHtml()
    {
        $js = 'new Craft.ElementActionTrigger({
                  handle: '.JsonHelper::encode($this->getClassHandle()).',
                  validateSelection: function($selectedElements){ return $selectedElements.length > 1; },
              });

              $("#TagManager_Merge-actiontrigger").on("click", function() {
                var elements = Craft.elementIndex.getSelectedElements();
                var list = $(".merge-tags-list");

                list.empty();

                $.each(elements, function(index, element) {
                    list.append("<li><a class=\'formsubmit\' data-param=\'merge_id\' data-value=" + element.dataset.id + ">" + element.innerText + "</a></li>");
                });

                $(document).ready(function() {
                    Craft.initUiElements();
                });

                $(document).on("click", ".formsubmit", function(ev) {
                    // Code adapted from formsubmit function in craft.js:1411

                    var $btn = $(ev.currentTarget);
                    var $form = $("#TagManager_Merge-actiontrigger");

              			if ($btn.attr("data-param"))
              			{
              				$("<input type=\'hidden\'/>")
              					.attr({
              						name: $btn.attr("data-param"),
              						value: $btn.attr("data-value")
              					})
              					.appendTo($form);
              			}

              			$form.submit();

                    $("#merge-tags-menu").hide();
              	});
              });';

        craft()->templates->includeJs($js);
        craft()->templates->includeCss('h3.merge-tags-intro { padding-top: 14px; }');

        return craft()->templates->render('tagmanager/_mergeTrigger');
    }

    public function isDestructive()
    {
        return true;
    }

    public function performAction(ElementCriteriaModel $criteria)
    {
        $mergeId = $this->getParams()->merge_id;
        $elementIds = $criteria->ids();

        foreach ($elementIds as $elementId)
        {
            if ($elementId != $mergeId) {
                if (!craft()->elements->mergeElementsByIds($elementId, $mergeId)) {
                    $this->setMessage(Craft::t('An error occurred while merging tags.'));
                    return false;
                }
            }
        }

        // Success!
        $this->setMessage(Craft::t('Tags merged successfully.'));
        return true;
    }

    protected function defineParams()
    {
        return array(
            'merge_id' => array(AttributeType::Number, 'required' => true)
        );
    }
}
