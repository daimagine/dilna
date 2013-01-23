<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Adi
 * Date: 1/9/13
 * Time: 3:38 PM
 * To change this template use File | Settings | File Templates.
 */

return array(

    /*
     |--------------------------------------------------------------------------
     | Default Role ID
     |--------------------------------------------------------------------------
     */

    'role' => array(
        'admin' => 1
    ),

    /*
     |--------------------------------------------------------------------------
     | Default Print template parameter
     |--------------------------------------------------------------------------
     */

    'print' => array(
        'account' => array(
            'signed' => array(
                'name'      => 'Adi Kurniawan',
                'position'  => 'Divisi Accounting',
            )
        )
    ),

    /*
     |--------------------------------------------------------------------------
     | Settlement Reminder Scheduler
     |--------------------------------------------------------------------------
     */

    'scheduler' => array(
        'settlement' => array(
            //days before settlement overdue
            'day_due'   => 3,
            //database key in system_properties
            'db_key'    => 'SETTLEMENT_REMINDER_KEY'
        ),
    ),

);