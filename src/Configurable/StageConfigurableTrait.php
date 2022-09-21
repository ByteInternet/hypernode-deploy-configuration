<?php

declare(strict_types=1);

namespace Hypernode\DeployConfiguration\Configurable;


use Hypernode\DeployConfiguration\Stage;

trait StageConfigurableTrait
{
    /**
     * When set the configuration is only applied on certain stage
     *
     * @var Stage
     */
    private $stage;

    /**
     * @return Stage
     */
    public function getStage(): ?Stage
    {
        return $this->stage;
    }

    /**
     * @param Stage $stage
     * @return self
     */
    public function setStage(Stage $stage): self
    {
        $this->stage = $stage;
        return $this;
    }
}
