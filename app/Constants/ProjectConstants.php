<?php

namespace App\Constants;

class ProjectConstants
{
    // Project status constants
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    //role constant
    const ROLE_ADMIN = 1;
    const ROLE_CLIENT = 2;
    const ROLE_FREELANCE = 3;
    const SETTING_TABLE_ID = 1;
    const CONTENT_IMAGE = 'content-image';
    

    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED = 202;
    const HTTP_NO_CONTENT = 204;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const RESET_PASSWORD_LINK = 'https://clinicfront.obsidiantechno.com/reset-password/';
    const FRONTEND_PATH = 'https://clinicfront.obsidiantechno.com';

   
}
