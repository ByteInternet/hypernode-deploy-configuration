<?php

declare(strict_types=1);

namespace Hypernode\DeployConfiguration;


interface StageConfigurableInterface
{
    /**
     * @param Stage $stage
     * @return self
     */
    public function setStage(Stage $stage);

    /**
     * @return Stage|null
     */
    public function getStage(): ?Stage;
}
