<?php
/**
 * GET /members/ | Get data books.
 */

use App\Functions\Controllers\Auth;

$run = function () {
    App\Controllers\Controller::Auth()->guestLogin(); 
};

