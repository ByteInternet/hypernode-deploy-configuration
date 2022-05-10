<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2020
 */
declare(strict_types = 1);

namespace HipexDeployConfiguration;


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