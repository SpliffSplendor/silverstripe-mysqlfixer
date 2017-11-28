<?php

/**
 * This is intended to _fix_ problem
 * `Expression #1 of ORDER BY clause is not in SELECT list.....which is not in SELECT list; this is incompatible with DISTINCT` caused by some modules.
 *
 * The distinct _error_ occures with MySQL >5.7.5 where the option __ONLY_FULL_GROUP_BY__ became part of the combination on __ANSI__
 * (which also includes 'REAL_AS_FLOAT, PIPES_AS_CONCAT, ANSI_QUOTES, IGNORE_SPACE') used by SilverStripe for sql_mode.
 * See also https://dev.mysql.com/doc/refman/5.7/en/sql-mode.html#sql-mode-combo
 *
 * @inheritdoc
 */

class MySQL5720Database extends MySQLDatabase
{

    public function connect($parameters)
    {
        // Ensure that driver is available (required by PDO)
        if (empty($parameters['driver']))
        {
            $parameters['driver'] = $this->getDatabaseServer();
        }

        // Set charset
        if (empty($parameters['charset'])
            && ($charset = Config::inst()->get('MySQLDatabase', 'connection_charset'))
        )
        {
            $parameters['charset'] = $charset;
        }

        // Set collation
        if (empty($parameters['collation'])
            && ($collation = Config::inst()->get('MySQLDatabase', 'connection_collation'))
        )
        {
            $parameters['collation'] = $collation;
        }

        // Notify connector of parameters
        $this->connector->connect($parameters);

        // This is important!
        //$this->setSQLMode('ANSI');
        //omit ONLY_FULL_GROUP_BY which became part of the mysql 5.7.5 combo 'ANSI'
        // @see https://dev.mysql.com/doc/refman/5.7/en/sql-mode.html#sql-mode-combo
        $this->setSQLMode("REAL_AS_FLOAT,PIPES_AS_CONCAT,ANSI_QUOTES,IGNORE_SPACE");

        if (isset($parameters['timezone']))
        {
            $this->selectTimezone($parameters['timezone']);
        }

        // SS_Database subclass maintains responsibility for selecting database
        // once connected in order to correctly handle schema queries about
        // existence of database, error handling at the correct level, etc
        if (! empty($parameters['database']))
        {
            $this->selectDatabase($parameters['database'], false, false);
        }
    }

}
