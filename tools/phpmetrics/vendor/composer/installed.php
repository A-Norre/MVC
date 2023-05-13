<?php return array(
    'root' => array(
        'name' => '__root__',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => 'bc3dd7a998ebab6a50420300d8df49fe614034ac',
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        '__root__' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'bc3dd7a998ebab6a50420300d8df49fe614034ac',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'halleck45/php-metrics' => array(
            'dev_requirement' => true,
            'replaced' => array(
                0 => '*',
            ),
        ),
        'halleck45/phpmetrics' => array(
            'dev_requirement' => true,
            'replaced' => array(
                0 => '*',
            ),
        ),
        'nikic/php-parser' => array(
            'pretty_version' => 'v4.15.4',
            'version' => '4.15.4.0',
            'reference' => '6bb5176bc4af8bcb7d926f88718db9b96a2d4290',
            'type' => 'library',
            'install_path' => __DIR__ . '/../nikic/php-parser',
            'aliases' => array(),
            'dev_requirement' => true,
        ),
        'phpmetrics/phpmetrics' => array(
            'pretty_version' => 'v2.8.2',
            'version' => '2.8.2.0',
            'reference' => '4b77140a11452e63c7a9b98e0648320bf6710090',
            'type' => 'library',
            'install_path' => __DIR__ . '/../phpmetrics/phpmetrics',
            'aliases' => array(),
            'dev_requirement' => true,
        ),
    ),
);
