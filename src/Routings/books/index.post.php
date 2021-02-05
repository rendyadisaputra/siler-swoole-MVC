<?php
/**
 * GET /books/ | Get data books.
 */
use App\Controllers\Controller;

$run = function () {Controller::Book()->postBook(); };
