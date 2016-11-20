<?php

namespace Outpost\Console;

use Outpost\Console\Responders\Templates\TemplateIndexResponder;
use Outpost\Console\Responders\Templates\TemplatePreviewResponder;
use Outpost\Files\Directory;
use Outpost\Site;
use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\Request;

class ConsoleSite extends Site
{
    /**
     * @var string
     */
    protected $publicPath;

    protected $site;

    public function __construct(SiteInterface $site)
    {
        $this->site = $site;
    }

    /**
     * @return string
     */
    public function getPublicPath()
    {
        return $this->publicPath;
    }

    public function getPym()
    {
        return $this->getAssetContents('pym/pym.js');
    }

    public function getScript()
    {
        return $this->getJquery() . $this->getPym() . $this->getAssetContents('outpost.js');
    }

    public function getSite()
    {
        return $this->site;
    }

    public function getStylesheet()
    {
        return file_get_contents(__DIR__ . '/../../assets/outpost.css');
    }

    public function respond(Request $request)
    {
        $requestPath = $request->getPathInfo();
        if (!empty($this->publicPath)) {
            $path = $this->publicPath . DIRECTORY_SEPARATOR . ltrim($requestPath, '/');
            if (is_file($path)) {
                $extension = null;
                if (false !== $pos = strrpos(basename($path), '.')) {
                    $extension = substr(basename($path), $pos + 1);
                }
                switch ($extension) {
                    case 'css':
                        $mimeType = 'text/css';
                        break;
                    case 'js':
                        $mimeType = 'text/javascript';
                        break;
                    default:
                        $mimeType = mime_content_type($path);
                }
                if (isset($mimeType)) {
                    header("Content-Type: $mimeType");
                }
                readfile($path);
                return null;
            }
        }
        if ($requestPath == '/favicon.ico') {
            return null;
        }
        return parent::respond($request);
    }

    /**
     * @param string $path
     */
    public function setPublicPath($path)
    {
        $this->publicPath = $path;
    }

    protected function getAssetContents($path)
    {
        return file_get_contents(__DIR__ . "/../../assets/{$path}");
    }

    protected function getJquery()
    {
        return $this->getAssetContents('jquery/jquery.js');
    }

    protected function getTemplatesDirectory()
    {
        return new Directory($this->getTemplatesPath());
    }

    protected function getTemplatesPath()
    {
        return __DIR__ . '/../../templates';
    }

    protected function makeRouter()
    {
        $router = parent::makeRouter();
        $router->route('GET', '_outpost/templates', new TemplateIndexResponder());
        $router->route('GET', '_outpost/templates/preview', new TemplatePreviewResponder());
        return $router;
    }

    protected function makeTwigLoader()
    {
        return new \Twig_Loader_Filesystem($this->getTemplatesPath());
    }
}