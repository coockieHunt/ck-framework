<?php
namespace ck_framework\TwigExtension;


use ck_framework\Session\FlashService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FlashTwigExtension extends AbstractExtension
{

    /**
     * @var FlashService
     */
    private $flashService;

    public function __construct(FlashService $flashService)
    {
        $this->flashService = $flashService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('flash', [$this, 'getFlash'])
        ];
    }

    public function getFlash($type)
    {
        if ($this->flashService->get($type)){
            return $this->flashService->get($type);
        }else{
            return null;
        }
    }
}
