<?php
namespace Cogipix\CogimixDailymotionBundle\ViewHooks\Javascript;
use Cogipix\CogimixCommonBundle\ViewHooks\Javascript\JavascriptImportInterface;

/**
 *
 * @author plfort - Cogipix
 *
 */
class JavascriptImportRenderer implements JavascriptImportInterface
{

    public function getJavascriptImportTemplate()
    {
        return 'CogimixDailymotionBundle::js.html.twig';
    }

}
