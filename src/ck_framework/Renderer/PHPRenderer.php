<?php


namespace ck_framework\Renderer;


/**
 * @method addPatch(string $defaultPatch)
 */
class PHPRenderer implements RendererInterface
{

    Const DEFAULT_NAMESPACE = "__CKMAIN";
    private $Patch;
    private $Global = [];

    /**
     * PHPRenderer constructor.
     * @param string $defaultPatch
     */
    public function __construct(string $defaultPatch)
    {
        $this->addPatch($defaultPatch);
    }

    /**
     * Add Patch view folder
     * @param string $namespace
     * @param string|null $patch
     */
    public function addPath(string $patch, string $namespace = null): void
    {
        if ($namespace != null) {
            $this->Patch[$namespace] = $patch;
        } else
            $this->Patch[self::DEFAULT_NAMESPACE] = $patch;

    }

    /**
     * render view
     * @param string $view
     * @param array $params
     * @return false|string
     */
    public function Render(string $view, array $params = []): string
    {
        $namespace = substr($view, 1, strpos($view, '/') - 1);
        $file = substr($view, strpos($view, '/') + 1);
        $patch = $this->Patch[$namespace];
        extract($params);
        extract($this->Global);
        ob_start();
        $end = 'View.php';
        require($patch . DIRECTORY_SEPARATOR . $file . $end);
        return ob_get_clean();
    }

    public function addGlobal(string $key, $value)
    {
        $this->Global[$key] = $value;
    }
}