<?php
/**
 * GET /articles/ | Get data books.
 */
use App\Controllers\Controller;

$run = function () {Controller::Articles()->postArticle(); };
