<?php


namespace ck_framework\Pagination;


class Pagination
{

    /**
     * @var int
     */
    private $DbElementDisplay;
    /**
     * @var int
     */
    private $BarElementDisplay;
    /**
     * @var int
     */
    private $CurrentStep;
    /**
     * @var int
     */
    private $number_step;
    /**
     * @var string
     */
    private $redirect_uri;

    /**
     * Pagination constructor.
     * @param int $db_element_display
     * @param int $bar_element_display
     * @param int $number_db_element
     * @param string $redirect_uri
     */
    public function __construct(int $db_element_display, int $bar_element_display, int $number_db_element, string $redirect_uri)
    {
        $this->DbElementDisplay = $db_element_display;
        $this->BarElementDisplay = $bar_element_display;
        $this->redirect_uri = $redirect_uri;


        $this->number_step = ceil($number_db_element / $this->DbElementDisplay);

        $this->setCurrentStep(0);
    }

    public function GetMaxUserStep(){
        dd($this->DbElementDisplay);
        return $this->DbElementDisplay + 1;
    }

    /**
     * Get limit value for database request
     * @return int
     */
    public function GetLimit(): int{
        $offset = ($this->getCurrentStep() * $this->getDbElementDisplay()) - $this->getDbElementDisplay();
        return $offset;
    }

    /**
     * add one step
     */
    public  function nextStep(){
        $this->setCurrentStep($this->getCurrentStep() + 1);
    }

    /**
     * @return int
     */
    public function getCurrentStep(): int
    {
        return $this->CurrentStep;
    }

    /**
     * @param int $CurrentStep
     */
    public function setCurrentStep(int $CurrentStep): void
    {
        $this->CurrentStep = $CurrentStep;
    }

    /**
     * @return int
     */
    public function getBarElementDisplay(): int
    {
        return $this->BarElementDisplay;
    }

    /**
     * @return int
     */
    public function getDbElementDisplay(): int
    {
        return $this->DbElementDisplay;
    }

    /**
     * @return int
     */
    public function getNumberStep(): int
    {
        return $this->number_step;
    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirect_uri;
    }
}