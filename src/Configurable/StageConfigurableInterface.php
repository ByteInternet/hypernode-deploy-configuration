<?php

declare(strict_types=1);

namespace Hypernode\DeployConfiguration\Configurable;


use Hypernode\DeployConfiguration\Stage;

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
