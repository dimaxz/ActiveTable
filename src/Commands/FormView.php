<?php


namespace ActiveTable\Commands;


use ActiveTable\Contracts\CommandInterface;
use ActiveTable\Contracts\FormInterface;
use ActiveTable\Contracts\OutputInterface;

/**
 * отображение формы
 * Class FormView
 * @package ActiveTable\Commands
 */
class FormView implements CommandInterface
{

    protected $output;
    protected $form;

    public function __construct(OutputInterface $output, FormInterface $form)
    {
        $this->output = $output;
        $this->form = $form;
    }

    /**
     *
     */
    public function process(): void
    {
        $this->output->addContent(
            $this->form->render()
        );
    }

}