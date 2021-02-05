<?php

use App\Models\Model;

function postBook($resp)
{
    $BookModel = Model::BookModel();
    $booksData = $BookModel->createBook();
    // var_dump($BookModel->table);
    $resp->sendResponse($booksData, 200);
}
