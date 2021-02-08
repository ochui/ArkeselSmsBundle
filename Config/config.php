<?php

return [
    'services' => [
        'other' => [
            'mautic.sms.arkesel.configuration' => [
                'class'        => MauticPlugin\ArkeselSmsBundle\Integration\Arkesel\Configuration::class,
                'arguments'    => [
                    'mautic.helper.integration',
                ],
            ],
            'mautic.sms.arkesel.transport' => [
                'class'        => MauticPlugin\ArkeselSmsBundle\Integration\Arkesel\ArkeselTransport::class,
                'arguments'    => [
                    'mautic.sms.arkesel.configuration',
                    'monolog.logger.mautic',
                ],
                'tag'          => 'mautic.sms_transport',
                'tagArguments' => [
                    'integrationAlias' => 'Arkesel',
                ],
                'serviceAliases' => [
                    'sms_api',
                    'mautic.sms.api',
                ],
            ],
            'mautic.sms.arkesel.callback' => [
                'class'     => MauticPlugin\ArkeselSmsBundle\Integration\Arkesel\ArkeselCallback::class,
                'arguments' => [
                    'mautic.sms.helper.contact',
                    'mautic.sms.twilio.configuration',
                ],
                'tag'   => 'mautic.sms_callback_handler',
            ],
        ],
        'integrations' => [
            'mautic.integration.arkesel' => [
                'class'     => MauticPlugin\ArkeselSmsBundle\Integration\ArkeselIntegration::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.cache_storage',
                    'doctrine.orm.entity_manager',
                    'session',
                    'request_stack',
                    'router',
                    'translator',
                    'logger',
                    'mautic.helper.encryption',
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.paths',
                    'mautic.core.model.notification',
                    'mautic.lead.model.field',
                    'mautic.plugin.model.integration_entity',
                    'mautic.lead.model.dnc',
                ],
            ],
        ],
    ],
    'menu' => [
        'main' => [
            'items' => [
                'mautic.sms.smses' => [
                    'route'  => 'mautic_sms_index',
                    'access' => ['sms:smses:viewown', 'sms:smses:viewother'],
                    'parent' => 'mautic.core.channels',
                    'checks' => [
                        'integration' => [
                            'Arkesel' => [
                                'enabled' => true,
                            ],
                        ],
                    ],
                    'priority' => 70,
                ],
            ],
        ],
    ],
    'parameters' => [
        'sms_enabled'              => false,
        'sms_username'             => null,
        'sms_password'             => null,
        'sms_sending_phone_number' => null,
        'sms_frequency_number'     => 0,
        'sms_frequency_time'       => 'DAY',
        'sms_transport'            => 'mautic.sms.arkesel.transport',
    ],
];
