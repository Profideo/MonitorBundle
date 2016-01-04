<?php

namespace Profideo\MonitorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Florent SEVESTRE <fsevestre@profideo.com>
 */
class ProfideoMonitorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (empty($config['checks'])) {
            return;
        }

        foreach ($config['checks'] as $check => $values) {
            if (empty($values)) {
                continue;
            }

            $loader->load(sprintf('checks/%s.xml', $check));
            $prefix = sprintf('%s.check.%s', $this->getAlias(), $check);

            switch ($check) {
                case 'table_row_count':
                    $container->setParameter($prefix, $values);
                    continue;
            }

            if (is_array($values)) {
                foreach ($values as $key => $value) {
                    $container->setParameter($prefix.'.'.$key, $value);
                }
            }
        }
    }
}
