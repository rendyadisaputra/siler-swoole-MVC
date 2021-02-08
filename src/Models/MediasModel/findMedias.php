<?php

$findMedias = function ($mongoModel, $args) {
    $filterQuery = null;
    if (!empty($args)) {
        $filterQuery = $args[0];
        if (isset($filterQuery['_id']) && is_string($filterQuery['_id'])) {
            $filterQuery['_id'] = $mongoModel->convertToObjectId($filterQuery['_id']);
        }
    }

    $pipeline = [
        [
            '$match' => [
                'deleted' => [
                    '$exists' => false,
                ],
            ],
        ],
    ];

    if ($filterQuery) {
        $pipeline[0]['$match'] = array_merge($pipeline[0]['$match'], $filterQuery);
    }
    // var_dump($filterQuery);
    // var_dump(json_encode($pipeline, JSON_PRETTY_PRINT));
    $Medias = $mongoModel->DBAggregate($pipeline);

    // var_dump($getMembersData);

    return $Medias;
};
