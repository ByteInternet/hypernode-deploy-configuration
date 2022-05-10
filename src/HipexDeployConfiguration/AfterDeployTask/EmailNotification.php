<?php
/**
 * @author Hipex <info@hipex.io>
 * @copyright (c) Hipex B.V. 2018
 */

namespace HipexDeployConfiguration\AfterDeployTask;

use HipexDeployConfiguration\Exception\EnvironmentVariableNotDefinedException;
use function HipexDeployConfiguration\getenv;
use HipexDeployConfiguration\ServerRoleConfigurableInterface;
use HipexDeployConfiguration\ServerRoleConfigurableTrait;
use HipexDeployConfiguration\StageConfigurableInterface;
use HipexDeployConfiguration\StageConfigurableTrait;
use HipexDeployConfiguration\TaskConfigurationInterface;

class EmailNotification implements
    TaskConfigurationInterface,
    ServerRoleConfigurableInterface,
    StageConfigurableInterface
{
    use ServerRoleConfigurableTrait;

    use StageConfigurableTrait;

    /**
     * @var array|string[]
     */
    private $email;

    /**
     * @var string
     */
    private $emailFrom;

    /**
     * @var string
     */
    private $smtpServer;

    /**
     * @var string
     */
    private $smtpUser;

    /**
     * @var string
     */
    private $smtpPassword;

    /**
     * NewRelic constructor.
     *
     * @param string[]|null $email Defaults to env `NOTIFICATION_EMAIL_TO`
     * @param string|null $emailFrom Defaults to env `NOTIFICATION_EMAIL_FROM`
     * @param string|null $smtpServer Defaults to env `SMTP_SERVER`
     * @param string|null $smtpUser Defaults to env `SMTP_USER`
     * @param string|null $smtpPassword Defaults to env `SMTP_PASS`
     * @throws EnvironmentVariableNotDefinedException
     */
    public function __construct(array $email = null, string $emailFrom = null, string $smtpServer = null, string $smtpUser = null, string $smtpPassword = null)
    {
        $this->email = $email ?: getenv('NOTIFICATION_EMAIL_TO');
        $this->emailFrom = $emailFrom ?: getenv('NOTIFICATION_EMAIL_FROM');
        $this->smtpServer = $smtpServer ?: getenv('SMTP_SERVER');
        $this->smtpUser = $smtpUser ?: getenv('SMTP_USER');
        $this->smtpPassword = $smtpPassword ?: getenv('SMTP_PASS');
    }

    /**
     * @return array|string[]
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getEmailFrom(): string
    {
        return $this->emailFrom;
    }

    /**
     * @return string
     */
    public function getSmtpServer(): string
    {
        return $this->smtpServer;
    }

    /**
     * @return string
     */
    public function getSmtpUser(): string
    {
        return $this->smtpUser;
    }

    /**
     * @return string
     */
    public function getSmtpPassword(): string
    {
        return $this->smtpPassword;
    }
}
