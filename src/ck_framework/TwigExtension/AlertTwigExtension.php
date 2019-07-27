<?php


namespace ck_framework\TwigExtension;


use ck_framework\Flash\Flash;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AlertTwigExtension extends AbstractExtension
{
    /**
     * @var Flash
     */
    private $flash;

    /**
     * AlertTwigExtension constructor.
     * @param Flash $flash
     */
    public function __construct(Flash $flash)
    {
        $this->flash = $flash;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction("alert", [$this, 'AlertPush']),
        ];
    }

    public function AlertPush(?int $id = null): void
    {

    }
}