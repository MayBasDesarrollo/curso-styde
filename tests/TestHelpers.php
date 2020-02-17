<?php

namespace Tests;


trait TestHelpers
{
    protected function assertDatabaseEmpty($table, $connection = null)
    {
        $total = $this->getConnection($connection)->table($table)->count();
        $this->assertSame(0, $total, sprintf(
            "Failed asserting the table [%s] is empty. %s %s found.", $table, $total, str_plural('row', $total)
        ));
    }

    /* protected function assertDatabasecount($table, $connection = null)
    {
        $total = $this->getConnection($connection)->table($table)->count();
        $this->assertSame(1, $total, sprintf(
            "Failed asserting the table [%s] is empty. %s %s found.", $table, $total, str_plural('row', $total)
        ));
    } */

    protected function withData(array $custom = [])
    {
        return array_merge($this->defaulData(), $custom);
    }

    protected function defaulData()
    {
        return $this->defaulData;
    }
}