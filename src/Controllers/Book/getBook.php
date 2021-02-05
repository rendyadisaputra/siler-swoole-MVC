<?php

use App\Models\Model;
use Siler\Http\Request;

function getBook($resp)
{
    Request\get();
    $BookModel = (new Model())->BookModel()->init();
    $booksData = $BookModel->getBooks();
    // var_dump($BookModel->table);
    $resp->sendResponse($booksData, 200);
}
