<?php


namespace ck_framework\TwigExtension;

use ck_framework\Utils\SnippetUtils;
use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class AssetTwigExtension extends AbstractExtension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $dir;

    /**
     * AssetTwigExtension constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        if ($this->dir == null){
            $this->dir = $container->get('default.style.src');
        }
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction("css", [$this, 'load_css']),
            new TwigFunction("js", [$this, 'load_js']),
            new TwigFunction("img", [$this, 'load_img'])
        ];
    }

    public function load_css(string $link , ?bool $locally = true): void
    {
        if ($locally){
            $dir = $this->dir;
            $link = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/'. $dir .'/css/' . $link;
        }

        echo "<link rel='stylesheet' type='text/css' href='{$link}'>";
    }

    public function load_js(string $link, ?string $script = null, ?bool $locally = true): void
    {
        if ($locally){
            $dir = $this->dir;
            $link = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/'. $dir .'/js/' . $link;
        }

        echo "<script src='{$link}' type='text/javascript'>{$script}</script>";
    }

    public function load_img(string $link, array $args = [], ?bool $locally = true): void
    {
        if ($locally){
            $dir = $this->dir;
            $link = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/'. $dir .'/img/' . $link;
        }

        $args = SnippetUtils::ArrayArgsToHtml($args);

        echo " <img {$args} src='{$link}'>";
    }

}