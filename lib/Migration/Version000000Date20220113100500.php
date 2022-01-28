<?php

namespace OCA\IntranetAgglo\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\SimpleMigrationStep;
use OCP\Migration\IOutput;

class Version000000Date20220113100500 extends SimpleMigrationStep
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
                'notnull' => true,
                'length' => 200,
            ]);
            $table->addColumn('title', 'string', [
                'notnull' => true,
                'length' => 200
            ]);
            $table->addColumn('text', 'text', [
                'notnull' => true,
                'default' => ''
            ]);
            $table->addColumn('photo', 'text', [
                'notnull' => true,
                'default' => ''
            ]);
            $table->addColumn('category', 'integer', [
                'notnull' => true
            ]);
            $table->addColumn('authgroup', 'text', [
                'notnull' => true,
                'default' => ''
            ]);
            $table->addColumn('size', 'integer', [
                'notnull' => true
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
            $table->addColumn('position', 'string', [
                'default' => '',
                'length' => 16
            ]);
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
