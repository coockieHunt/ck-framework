<?php


namespace ck_framework\Flash\Flash;


class FlashMessage
{
    private $content;

    private $type;

    public function __construct(string $content, string $type)
    {
        $this->content = $content;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}