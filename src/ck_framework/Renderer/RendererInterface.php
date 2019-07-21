<?php

namespace ck_framework\Renderer;

interface RendererInterface
{
    /**
     * Add Patch view folder
     * @param string $namespace
     * @param string|null $patch
     */
    public function addPath(string $patch, string $namespace = null): void;

    /**
     * render view
     * @param string $view
     * @param array $params
     * @return false|string
     */
    public function Render(string $view, array $params = []): string;

    public function addGlobal(string $key, $value);
}