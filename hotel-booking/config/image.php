<?php

return [
    "image_validate_config" => [
        "required",
        "image",
        "mimes:png,jpg,jpeg,webp",
        "max:5120",
    ],
];