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
     * Pagination constructor.
     * @param int $db_element_display
     * @param int $bar_element_display
     * @param int $number_db_element
     */
    public function __construct(int $db_element_display, int $bar_element_display, int $number_db_element)
    {
        $this->DbElementDisplay = $db_element_display;
        $this->BarElementDisplay = $bar_element_display;

        $this->number_step = ceil($number_db_element / $this->DbElementDisplay);

        $this->setCurrentStep(0);
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
}