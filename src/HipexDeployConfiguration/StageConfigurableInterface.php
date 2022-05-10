<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2020
 */
declare(strict_types = 1);

namespace HipexDeployConfiguration;


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