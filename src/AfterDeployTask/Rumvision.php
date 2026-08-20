<?php

namespace Hypernode\DeployConfiguration\AfterDeployTask;

use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\ServerRoleConfigurableTrait;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableInterface;
use Hypernode\DeployConfiguration\Configurable\StageConfigurableTrait;
use Hypernode\DeployConfiguration\TaskConfigurationInterface;

class Rumvision implements
    TaskConfigurationInterface,
    ServerRoleConfigurableInterface,
    StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;

    use StageConfigurableTrait;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string[]
     */
    private $domains;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $namePrefix;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $urlScope;

    /**
     * @var string[]|null
     */
    private $urlCategoryIds;

    /**
     * @var string|null
     */
    private $impactAt;

    /**
     * @var string|null
     */
    private $categorySlug;

    /**
     * @param string        $apiKey         RUMvision API key, used as Bearer token
     * @param string[]      $domains        Domain names the annotation applies to
     * @param string|null   $title          Title of the annotation (max 255 characters), defaults to release name
     * @param string|null   $namePrefix     String prefixed to the title
     * @param string|null   $description    More details about the release
     * @param string|null   $urlScope       URL scope for the annotation, `all` or `restricted`
     * @param string[]|null  $urlCategoryIds URL category IDs (UUIDs), required when $urlScope is `restricted`
     * @param string|null   $impactAt       Impact timestamp (ISO 8601), defaults to the current time
     * @param string|null   $categorySlug   Annotation category slug, defaults to `hosting`
     */
    public function __construct(
        string $apiKey,
        array $domains,
        string $title = null,
        string $namePrefix = null,
        string $description = null,
        string $urlScope = null,
        array $urlCategoryIds = null,
        string $impactAt = null,
        string $categorySlug = null
    ) {
        $this->apiKey = $apiKey;
        $this->domains = $domains;
        $this->title = $title;
        $this->namePrefix = $namePrefix;
        $this->description = $description;
        $this->urlScope = $urlScope;
        $this->urlCategoryIds = $urlCategoryIds;
        $this->impactAt = $impactAt;
        $this->categorySlug = $categorySlug;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return string[]
     */
    public function getDomains(): array
    {
        return $this->domains;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getNamePrefix(): ?string
    {
        return $this->namePrefix;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getUrlScope(): ?string
    {
        return $this->urlScope;
    }

    /**
     * @return string[]|null
     */
    public function getUrlCategoryIds(): ?array
    {
        return $this->urlCategoryIds;
    }

    public function getImpactAt(): ?string
    {
        return $this->impactAt;
    }

    public function getCategorySlug(): ?string
    {
        return $this->categorySlug;
    }
}
