<?php

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use umi\orm\metadata\ICollectionDataSource;

return function (ICollectionDataSource $dataSource) {

    $masterServer = $dataSource->getMasterServer();
    $schemaManager = $masterServer
        ->getConnection()
        ->getSchemaManager();
    $tableScheme = new Table($dataSource->getSourceName());

    $tableScheme->addOption('engine', 'InnoDB');

    $tableScheme
        ->addColumn('id', Type::INTEGER)
        ->setAutoincrement(true);
    $tableScheme
        ->addColumn('guid', Type::STRING)
        ->setNotnull(false);
    $tableScheme
        ->addColumn('type', Type::TEXT)
        ->setNotnull(false);

    $tableScheme
        ->addColumn(
            'version',
            Type::INTEGER
        )
        ->setUnsigned(true)
        ->setDefault(1);

    $tableScheme
        ->addColumn('user_id', Type::INTEGER)
        ->setNotnull(false);
    $tableScheme
        ->addColumn('city_id', Type::INTEGER)
        ->setNotnull(false);
    $tableScheme
        ->addColumn('name', Type::STRING)
        ->setNotnull(false);
    $tableScheme
        ->addColumn('org_name', Type::STRING)
        ->setNotnull(false);

    $tableScheme->setPrimaryKey(['id']);
    $tableScheme->addUniqueIndex(['guid'], 'profile_guid');
    $tableScheme->addIndex(['user_id'], 'profile_user');
    $tableScheme->addIndex(['city_id'], 'profile_city');

    $tableScheme->addForeignKeyConstraint(
        'umi_mock_users',
        ['user_id'],
        ['id'],
        ['onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE'],
        'FK_profile_user'
    );
    $tableScheme->addForeignKeyConstraint(
        'umi_mock_cities',
        ['city_id'],
        ['id'],
        ['onUpdate' => 'CASCADE', 'onDelete' => 'CASCADE'],
        'FK_profile_city'
    );
    return $schemaManager->getDatabasePlatform()->getCreateTableSQL(
        $tableScheme,
        AbstractPlatform::CREATE_INDEXES | AbstractPlatform::CREATE_FOREIGNKEYS
    );
};
