<?php

namespace Outpost\Console\Responders\Content;

use Outpost\Console\ConsoleSite;

class ContentIndexResponder
{
    public function __invoke(ConsoleSite $site)
    {
        $classes = $site->getSite()->getLibraryClasses();
        $vars = $site->getTemplateVariables();
        $vars['classes'] = $classes;
        print $site->render("console/content/index.twig", $vars);
    }
}
