<?php

namespace Profideo\MonitorBundle\Check;

use Doctrine\Common\Persistence\ConnectionRegistry;
use ZendDiagnostics\Check\CheckCollectionInterface;

/**
 * @author Florent SEVESTRE <fsevestre@profideo.com>
 */
final class TableRowCountCollection implements CheckCollectionInterface
{
    /**
     * @var array
     */
    private $checks = array();

    /**
     * @param ConnectionRegistry $manager
     * @param array              $configs
     */
    public function __construct(ConnectionRegistry $manager, array $configs)
    {
        foreach ($configs as $groupName => $config) {
            $maxRows = isset($config['max_rows']) ? $config['max_rows'] : null;

            $check = new TableRowCount($manager, $config['tables'], $config['min_rows'], $maxRows);
            $check->setLabel(
                sprintf(
                    'Row count in "%s" table group (min: %d, max: %s)',
                    $groupName,
                    $config['min_rows'],
                    null !== $maxRows ? $maxRows : 'none'
                )
            );

            $this->checks[sprintf('table_row_count_%s', $groupName)] = $check;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getChecks()
    {
        return $this->checks;
    }
}
