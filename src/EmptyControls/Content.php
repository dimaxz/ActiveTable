<?php


namespace ActiveTable\EmptyControls;

use ActiveTable\Contracts\OutputInterface;

class Content implements OutputInterface
{
    /**
     * @var string
     */
    protected $content;

    public function addContent(string $buffer): void
    {
        $this->content.= $buffer;
    }

    public function getContent(): string
    {
        return  $this->content;
    }

    public function clear(): void
    {
        $this->content = "";
    }

}