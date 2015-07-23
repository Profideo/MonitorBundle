<?php

namespace Profideo\MonitorBundle\Check;

use Doctrine\Common\Persistence\ConnectionRegistry;
use ZendDiagnostics\Check\CheckCollectionInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TableRowCountCollection implements CheckCollectionInterface
{
    private $checks = array();

    public function __construct(ConnectionRegistry $manager, $tableNames, $minRowCount)
    {
        if (!is_array($tableNames)) {
            $tableNames = array($tableNames);
        }

        foreach ($tableNames as $tableName) {
            $check = new TableRowCount($manager, $tableName, $minRowCount);
            $check->setLabel(sprintf('Table Row Count on "%s"', $tableName));

            $this->checks[sprintf('table_row_count_%s', $tableName)] = $check;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getChecks()
    {
        return $this->checks;
    }
}
