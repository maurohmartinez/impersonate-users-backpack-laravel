<?php

return [
    'permission_key' => 'impersonate_users',
    'redirect_after_initiation' => config('backpack.dashboard'),
    'base_guard' => config('backpack.base.guard'),
    'session_key' => 'impersonate_user',
    'btn_custom_class' => null,
];