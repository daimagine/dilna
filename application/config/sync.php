<?php

return array(

    /*
     |--------------------------------------------------------------------------
     | Synchronize table list
     |--------------------------------------------------------------------------
     |
     | Table would be sync with online server.
     | Order table list by foreign table first before master table
     | to avoid constraint issues.
     |
     | Table listed here must have field 'updated_at' contains updated timestamp
     |
     | tables: 'access', 'role'.
     |
     */

    'tables' => array(
        'access',
        'account',
        'system_properties',
        'customer',
        'discount',
        'item_category',
        'service',
        'role',

        'user',
        'item_type',
        'unit_type',

        'membership',
        'role_access',
    ),

    /*
     |--------------------------------------------------------------------------
     | Synchronize table rows
     |--------------------------------------------------------------------------
     |
     | How many rows will be sent each table
     |
     | rows: 50.
     |
     */

    'rows' => 50,
    /*
     |--------------------------------------------------------------------------
     | Synchronize URL
     |--------------------------------------------------------------------------
     */

    'url' => array(
        'last_id'   => 'http://tech.springwizard.local/ebengkel/sync/production/fetch_last_id',
        'sync_data' => 'http://tech.springwizard.local/ebengkel/sync/production/sync_data',
    )

);