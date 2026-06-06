<?php

namespace App\Database\Connectors;

use Illuminate\Database\Connectors\PostgresConnector;

class NeonPostgresConnector extends PostgresConnector
{
    protected function addSslOptions($dsn, array $config)
    {
        $dsn = parent::addSslOptions($dsn, $config);

        if (isset($config['channel_binding'])) {
            $dsn .= ";channel_binding={$config['channel_binding']}";
        }

        return $dsn;
    }
}
