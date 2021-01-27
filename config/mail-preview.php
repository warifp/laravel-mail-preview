<?php

return [
    /*
     * This option determines where all the generated email previews will be
     * stored for the application. Typically, this is within the storage
     * directory. However, you may change the location as you desire.
     *
     */
    'path' => storage_path('email-previews'),

    /*
     * This option determines how long (in seconds) the mail transformer should
     * keep the generated preview files before deleting them. By default it's
     * set to 60 seconds, but you can change this to whatever you desire.
     *
     */
    'maximum_lifetime' => 60,

    /*
     * This option determines if you would like to show a HTML link at the top
     * left corner of your screen every time and email is sent from your
     * system, the link will point the browser to the preview file.
     *
     */
    'show_link_to_preview' => true,

    /*
     * Determines how long the preview pop up should remain visible.
     *
     * You can set this to `false` if the popup should stay visible.
     */
    'popup_timeout_in_seconds' => 8,

    /*
     * Most likely you don't have to touch this value, in this array all
     * middleware groups that you want to use this package with should
     * be included.
     */

    'middleware_groups' => [
        'web'
    ],

    /*
     * This option allows for setting middleware for the route that shows a
     * preview to the mail that was just sent.
     */
    'middleware' => [
        //
    ],
];
