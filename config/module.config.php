<?php

return [
    'service_manager' => [
        'factories' => [
            PortoSpire\SuiteCRMClient\Service\SuiteCrm::class => PortoSpireSuiteCRMClient\Service\SuiteCrmFactory::class,
        ]
    ]
];
