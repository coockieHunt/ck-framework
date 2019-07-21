<?php


namespace ck_framework\Renderer;


use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TwigRenderer implements RendererInterface
{
    /**
     * @var FilesystemLoader
     */
    private $loader;
    /**
     * @var Environment
     */
    private $twig;

    /**
     * TwigRenderer constructor.
     * @param string $defaultPatch
     * @param ContainerInterface $container
     * @throws LoaderError
     */
    public function __construct(string $defaultPatch, ContainerInterface $container)
    {
        $this->loader = new FilesystemLoader($defaultPatch);
        $environment = $container->get("twig.environment");
        $dev = $container->get("development");

        $this->twig = new Environment($this->loader,$environment);

        if($dev){
            $this->twig->addExtension(new DebugExtension());
        }

        if ($container->has('twig.extension')) {
            $extension = $container->get('twig.extension');
            foreach ($extension as $element) {
                $this->twig->addExtension($container->get($element));
            }
        }

        $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'error';
        $this->addPath($dir, 'error');

    }

    /**
     * Add Patch view folder
     * @param string $namespace
     * @param string|null $patch
     * @throws LoaderError
     */
    public function addPath(string $patch, string $namespace = null): void
    {
        $this->loader->addPath($patch, $namespace);
    }

    /**
     * render view
     * @param string $view
     * @param array $params
     * @return false|string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function Render(string $view, array $params = []): string
    {
        return $this->twig->render($view . 'View.twig', $params);
    }

    /**
     * add variable global render view
     * @param string $key
     * @param $value
     */
    public function addGlobal(string $key, $value): void
    {
        $this->twig->addGlobal($key, $value);
    }
}