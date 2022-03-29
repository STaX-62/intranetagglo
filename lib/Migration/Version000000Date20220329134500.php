<?php

namespace OCA\IntranetAgglo\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\SimpleMigrationStep;
use OCP\Migration\IOutput;

class Version000000Date20220329134500 extends SimpleMigrationStep
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

        if ($schema->hasTable('intranetagglomenu')) {
            $table = $schema->getTable('intranetagglomenu');
            $table->dropColumn('position');
            $table->addColumn('sectionId', 'integer', [
                'notnull' => true,
            ]);
            $table->addColumn('menuId', 'integer', [
                'notnull' => true,
            ]);
            $table->addColumn('submenuId', 'integer', [
                'notnull' => true,
            ]);
        }
        return $schema;
    }
}
