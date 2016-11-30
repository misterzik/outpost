<?php

namespace Outpost\Console\Responders\Templates;

use Outpost\Console\ConsoleSite;

class TemplateIndexResponder
{
    public function __invoke(ConsoleSite $site)
    {
        $templates = $site->getSite()->getTemplates();
        $vars = $site->getTemplateVariables();
        if (!empty($_GET['template'])) {
            try {
                $vars['template'] = $templates->find($_GET['template']);
                print $site->render("console/templates/template.twig", $vars);
            } catch (\OutOfBoundsException $e) {
                header("HTTP/1.0 404 Not Found");
                print "Not found";
            }
        } else {
            $vars['templates'] = $templates;
            print $site->render("console/templates/index.twig", $vars);
        }

    }
}
