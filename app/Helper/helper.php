<?php

function ImageUrl($img)
{
    return config('app.base_url') . config('app.about_image_path') . $img;
};
