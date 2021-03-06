<?php

namespace OCA\IntranetAgglo\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\SimpleMigrationStep;
use OCP\Migration\IOutput;

class Version000000Date20220628120000 extends SimpleMigrationStep
{

    /**
     * @param IOutput $output
     * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
     * @param array $options
     * @return null|ISchemaWrapper
     */
    public function changeSchema(IOutput $output, Closure $schemaClosure, array $options)
    {
        /** @var ISchemaWrapper $schema */
        $schema = $schemaClosure();

        if (!$schema->hasTable('intranetagglonews')) {
            $table = $schema->createTable('intranetagglonews');
            $table->addColumn('id', 'integer', [
                'autoincrement' => true,
                'notnull' => true,
            ]);
            $table->addColumn('author', 'string', [
                'length' => 200,
            ]);
            $table->addColumn('title', 'text', [
                'default' => ''
            ]);
            $table->addColumn('subtitle', 'text', [
                'default' => ''
            ]);
            $table->addColumn('text', 'text', [
                'default' => ''
            ]);
            $table->addColumn('photo', 'text', [
                'default' => ''
            ]);
            $table->addColumn('category', 'string', [
                'length' => 300,
            ]);
            $table->addColumn('groups', 'text', [
                'default' => ''
            ]);
            $table->addColumn('time', 'integer', [
                'default' => 0,
            ]);
            $table->addColumn('visible', 'boolean', [
                'default' => false,
                'notnull' => false,
            ]);
            $table->addColumn('pinned', 'boolean', [
                'default' => false,
                'notnull' => false,
            ]);
            $table->addColumn('link', 'string', [
                'default' => ''
            ]);
            $table->addColumn('expiration', 'integer', [
                'default' => 0
            ]);
            $table->setPrimaryKey(['id']);
        }
        if (!$schema->hasTable('intranetagglomenu')) {
            $table = $schema->createTable('intranetagglomenu');
            $table->addColumn('id', 'integer', [
                'autoincrement' => true,
                'notnull' => true,
            ]);
            $table->addColumn('title', 'string', [
                'length' => 200,
            ]);
            $table->addColumn('icon', 'string', [
                'length' => 50
            ]);
            $table->addColumn('link', 'string', [
                'default' => '',
                'length' => 1000
            ]);
            $table->addColumn('groups', 'string', [
                'default' => '',
                'length' => 1000
            ]);
            $table->addColumn('sectionid', 'integer');
            $table->addColumn('menuid', 'integer');
            $table->addColumn('submenuid', 'integer');
            $table->setPrimaryKey(['id']);
        }
        if (!$schema->hasTable('intranetaggloapps')) {
            $table = $schema->createTable('intranetaggloapps');
            $table->addColumn('id', 'integer', [
                'autoincrement' => true,
                'notnull' => true,
            ]);
            $table->addColumn('title', 'string', [
                'length' => 200,
            ]);
            $table->addColumn('icon', 'string', [
                'length' => 50
            ]);
            $table->addColumn('link', 'string', [
                'default' => '',
                'length' => 1000
            ]);
            $table->addColumn('groups', 'string', [
                'default' => '',
                'length' => 1000
            ]);
            $table->setPrimaryKey(['id']);
        }
        return $schema;
    }
}
