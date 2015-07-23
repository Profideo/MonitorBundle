<?php

namespace Profideo\MonitorBundle\Check;

use Doctrine\Common\Persistence\ConnectionRegistry;
use ZendDiagnostics\Check\AbstractCheck;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Failure;

class TableRowCount extends AbstractCheck
{
    protected $manager;
    protected $connectionName;
    protected $tableName;
    protected $minRowCount;

    public function __construct(ConnectionRegistry $registry, $tableName = null, $minRowCount = 0)
    {
        $this->manager = $registry;
        $this->connectionName = null;
        $this->tableName = $tableName;
        $this->minRowCount = $minRowCount;
    }

    public function check()
    {
        try {
            $connection = $this->manager->getConnection($this->connectionName);
            $result = $connection->fetchColumn('SELECT COUNT(id) FROM ' . $this->tableName . ';');

            if($result >= $this->minRowCount) {
                return new Success($result . ' rows in ' . $this->tableName );
            }

            return new Failure($result . ' rows in ' . $this->tableName . ' (' . $this->minRowCount . ' required)');
        } catch (\Exception $e) {
            return new Failure('Exception : ' . $e->getMessage());
        }
    }
}

