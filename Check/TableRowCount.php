<?php

namespace Profideo\MonitorBundle\Check;

use Doctrine\Common\Persistence\ConnectionRegistry;
use ZendDiagnostics\Check\AbstractCheck;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Failure;

/**
 * @author Florent SEVESTRE <fsevestre@profideo.com>
 */
final class TableRowCount extends AbstractCheck
{
    /**
     * @var ConnectionRegistry
     */
    private $manager;

    /**
     * @var array
     */
    private $tableNames;

    /**
     * @var int
     */
    private $minRows;

    /**
     * @var int|null
     */
    private $maxRows;

    /**
     * @param ConnectionRegistry $registry
     * @param array              $tableNames
     * @param int                $minRows
     * @param int|null           $maxRows
     */
    public function __construct(ConnectionRegistry $registry, array $tableNames, $minRows, $maxRows = null)
    {
        $this->manager = $registry;
        $this->tableNames = $tableNames;
        $this->minRows = $minRows;
        $this->maxRows = $maxRows;
    }

    /**
     * @return Failure|Success
     */
    public function check()
    {
        $failure = false;

        $messages = array();

        foreach ($this->tableNames as $tableName) {
            try {
                $connection = $this->manager->getConnection();
                $result = $connection->fetchColumn(<<<SQL
SELECT COUNT(*) FROM $tableName;
SQL
                );

                $messages[] = sprintf('%s rows in "%s" table', $result, $tableName);

                if ($result < $this->minRows || (null !== $this->maxRows && $result > $this->maxRows)) {
                    $failure = true;
                }
            } catch (\Exception $e) {
                return new Failure('Exception : '.$e->getMessage());
            }
        }

        $messages = implode(', ', $messages);

        return $failure ? new Failure($messages) : new Success($messages);
    }
}
