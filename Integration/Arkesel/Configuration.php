<?php


namespace MauticPlugin\ArkeselSmsBundle\Integration\Arkesel;

use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\ArkeselSmsBundle\Exceptions\ConfigurationException;

class Configuration
{
    /**
     * @var IntegrationHelper
     */
    private $integrationHelper;

    /**
     * @var string
     */
    private $sendingPhoneNumber;

    /**
     * @var string
     */
    private $apiKey;


    /**
     * Configuration constructor.
     */
    public function __construct(IntegrationHelper $integrationHelper)
    {
        $this->integrationHelper = $integrationHelper;
    }

    /**
     * @return string
     *
     * @throws ConfigurationException
     */
    public function getSendingNumber()
    {
        $this->setConfiguration();

        return $this->sendingPhoneNumber;
    }


    /**
     * @return string
     *
     * @throws ConfigurationException
     */
    public function getApiKey()
    {
        $this->setConfiguration();

        return $this->apiKey;
    }




    /**
     * @throws ConfigurationException
     */
    private function setConfiguration()
    {
        if ($this->apiKey) {
            return;
        }

        $integration = $this->integrationHelper->getIntegrationObject('Arkesel');

        if (!$integration || !$integration->getIntegrationSettings()->getIsPublished()) {
            throw new ConfigurationException();
        }

        $this->sendingPhoneNumber = $integration->getIntegrationSettings()->getFeatureSettings()['sending_phone_number'];
        if (empty($this->sendingPhoneNumber)) {
            throw new ConfigurationException();
        }


        $keys = $integration->getDecryptedApiKeys();
        if (empty($keys['password'])) {
            throw new ConfigurationException();
        }

        $this->apiKey  = $keys['password'];
    }
}
