<?php
namespace Cogipix\CogimixDailymotionBundle\ViewHooks\Widget;
use Cogipix\CogimixCommonBundle\ViewHooks\Widget\WidgetRendererInterface;

/**
 *
 * @author plfort - Cogipix
 *
 */
class WidgetRenderer implements WidgetRendererInterface
{


    public function getWidgetTemplate()
    {
        return 'CogimixDailymotionBundle:Widget:widget.html.twig';
    }

    public function getParameters(){
        return array();
    }

}
