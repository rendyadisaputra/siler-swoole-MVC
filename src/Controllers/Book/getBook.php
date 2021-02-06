<?php

use App\Models\Model;

function getBook($resp)
{
    $BookModel = (new Model())->BookModel()->init();
    $booksData = $BookModel->getBooks();
    // var_dump($BookModel->table);
    $resp->sendResponse($booksData, 200);
}
