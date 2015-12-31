# ProfideoMonitorBundle

This bundle add new checks to the [LiipMonitorBundle](https://github.com/liip/LiipMonitorBundle).


## Installation ##

Install with composer:

    $ composer require profideo/monitorBundle

> **Note:** The LiipMonitorBundle will be automatically installed.

Then register the bundles in the `AppKernel.php` file:

```php
public function registerBundles()
{
    $bundles = array(
        // ...
            new Liip\MonitorBundle\LiipMonitorBundle(),
            new Profideo\MonitorBundle\ProfideoMonitorBundle(),
        // ...
    );

    return $bundles;
}
```

[Read more about the LiipMonitorBundle installation](https://github.com/liip/LiipMonitorBundle#installation).

## Enabling built-in health checks ##

To enable built-in health checks, add them to your `config.yml`

```yml
profideo_monitor:
    checks:
        table_row_count:
            security:
                tables:
                    - user
                    - role
                    - group
                min_rows: 1
```


## Available Built-in Health Checks ##

[See "Available Built-in Health Checks" of the LiipMonitorBundle](https://github.com/liip/LiipMonitorBundle#available-built-in-health-checks).

See "Full Default Config" below for a list of all built-in checks and their configuration.


## Running Checks ##

[See "Running Checks" of the LiipMonitorBundle](https://github.com/liip/LiipMonitorBundle#running-checks).


## Full Default Config ##

```yml
profideo_monitor:
    checks:

        # Validate that database tables have a certain number of rows
        table_row_count:

            # Prototype
            name:
                tables:               [] # Required, Example: ["user", "role", "group"]
                min_rows:             ~ # Required
                max_rows:             ~
```
