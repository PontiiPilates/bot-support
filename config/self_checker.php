<?php

return [
    "bearer" => 'Bearer ' . env('PDFM_BEARER'),
    "template" => env('PDFM_TEMPLATE'),
    "logoUrl" => 'https://www.theviralist.com/wp-content/uploads/2022/06/Funny-and-Cute-corgi-puppies-videos-compilation-2021-Cutest-corgis-300x200.jpg',
    "methods" => [
        "documents" => 'https://api.pdfmonkey.io/api/v1/documents',
        "documents_cards" => 'https://api.pdfmonkey.io/api/v1/document_cards',
    ]
];
