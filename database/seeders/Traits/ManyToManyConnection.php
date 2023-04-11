<?php

namespace Database\Seeders\Traits;

trait ManyToManyConnection
{
    /**
     * Make many to Many Connection
     *
     * @return void
     */
    public function  make_connection($collection1, $collection2 ,$connection, $count=1): void
    {
        foreach ( $collection1 as $item)
            for($i=$count; $i>0;$i--)
                $connection::firstOrCreate($connection::factory()->for(
                    $item)->for($collection2->random())->make()->toArray());
    }
}
