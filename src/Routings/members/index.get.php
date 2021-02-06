<?php
/**
 * GET /members/ | Get data books.
 */
$run = function () { App\Controllers\Controller::Members(['auth'=>true])->getMembers(); };
