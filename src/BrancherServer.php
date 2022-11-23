<?php

declare(strict_types=1);

namespace Hypernode\DeployConfiguration;

class BrancherServer extends Server
{
    public function getLabels(): array
    {
        return $this->getOptions()[self::OPTION_HN_BRANCHER_LABELS] ?? [];
    }

    /**
     * @param string[] $labels Labels to be applied to the brancher node
     * @return $this
     */
    public function setLabels(array $labels): self
    {
        $this->setOption(self::OPTION_HN_BRANCHER_LABELS, $labels);
        return $this;
    }

    public function getSettings(): array
    {
        return $this->getOptions()[self::OPTION_HN_BRANCHER_SETTINGS] ?? [];
    }

    /**
     * @param array $settings Settings to be applied to the brancher node
     * @return $this
     */
    public function setSettings(array $settings): self
    {
        $this->setOption(self::OPTION_HN_BRANCHER_SETTINGS, $settings);
        return $this;
    }
}
