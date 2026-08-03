<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Consent version
    |--------------------------------------------------------------------------
    |
    | Bumping this re-asks every visitor. Change it when the categories change,
    | never for a wording tweak: a choice made about a different set of
    | categories is not a choice about this one.
    |
    */

    'version' => 1,

    'cookie' => 'cookie_consent',

    /*
    | How long a choice stands before we ask again.
    */

    'lifetime_days' => 180,

];
