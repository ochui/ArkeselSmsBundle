<?php


namespace MauticPlugin\ArkeselSmsBundle\Integration\Arkesel;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\SmsBundle\Sms\TransportInterface;
use Psr\Log\LoggerInterface;
use MauticPlugin\ArkeselSmsBundle\Exceptions\ConfigurationException;
use MauticPlugin\ArkeselSmsBundle\Exceptions\ArkeselException;

class ArkeselTransport implements TransportInterface
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $sendingPhoneNumber;

    /**
     * TwilioTransport constructor.
     */
    public function __construct(Configuration $configuration, LoggerInterface $logger)
    {
        $this->logger        = $logger;
        $this->configuration = $configuration;
    }

    /**
     * @param string $content
     *
     * @return bool|string
     */
    public function sendSms(Lead $lead, $content)
    {
        $number = $lead->getLeadPhoneNumber();

        if (null === $number) {
            return false;
        }

        try {
            $this->configureClient();

            // $this->client->messages->create(
            //     $this->sanitizeNumber($number),
            //     [
            //         'from' => $this->sendingPhoneNumber,
            //         'body' => $content,
            //     ]
            // );

            // send sms ?

            return true;
        } catch (NumberParseException $exception) {
            $this->logger->addWarning(
                $exception->getMessage(),
                ['exception' => $exception]
            );

            return $exception->getMessage();
        } catch (ConfigurationException $exception) {
            $message = ($exception->getMessage()) ? $exception->getMessage() : 'mautic.sms.transport.twilio.not_configured';
            $this->logger->addWarning(
                $message,
                ['exception' => $exception]
            );

            return $message;
        } catch (ArkeselException $exception) {
            $this->logger->addWarning(
                $exception->getMessage(),
                ['exception' => $exception]
            );

            return $exception->getMessage();
        }
    }

    /**
     * @param string $number
     *
     * @return string
     *
     * @throws NumberParseException
     */
    private function sanitizeNumber($number)
    {
        $util   = PhoneNumberUtil::getInstance();
        $parsed = $util->parse($number, 'US');

        return $util->format($parsed, PhoneNumberFormat::E164);
    }

    /**
     * @throws ConfigurationException
     */
    private function configureClient()
    {
        if ($this->client) {
            // Already configured
            return;
        }


        

        // $this->sendingPhoneNumber = $this->configuration->getSendingNumber();
        // $this->apiKey =             $this->configuration->getApiKey();

        // // Initialize client here
        // $this->client             = null;

        // Configure client ?
    }
}
