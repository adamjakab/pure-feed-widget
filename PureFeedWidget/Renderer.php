<?php
/**
 * @Author: Adam Jakab
 * @Licence: GNU GPLv3
 * @Copyright (c) 2020. Pure Feed Widget
 * @Package PureFeedWidget
 */

namespace PureFeedWidget;


use Twig\Environment as TwigEnvironment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader as TwigFSLoader;

class Renderer
{
    /** @var string */
    private $projectRootFolder = "";

    /** @var TwigEnvironment */
    private $twig_environment;

    /**
     * Renderer constructor.
     * @param array $env_options
     */
    public function __construct(array $env_options = [])
    {
        $this->projectRootFolder = dirname(__DIR__);
        $this->twig_environment = $this->setupTwigEnvironment($env_options);
    }

    /**
     * @param string $template
     * @param array $context
     * @return string
     */
    public function render(string $template, array $context = [])
    {
        try {
            $template = $this->twig_environment->load($template);
            return $template->render($context);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param array $env_options
     * @return TwigEnvironment
     */
    protected function setupTwigEnvironment(array $env_options = [])
    {
        $loader = new TwigFSLoader($this->projectRootFolder . "/templates");

        $default_env_options = [
            'debug' => false,
            'auto_reload' => false,
            'strict_variables' => false
        ];

        $env_options = array_merge($default_env_options, $env_options);
        $env_options['cache'] = $this->projectRootFolder . "/tmp";

        return new TwigEnvironment($loader, $env_options);
    }
}