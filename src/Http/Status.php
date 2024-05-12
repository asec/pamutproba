<?php

namespace PamutProba\Http;

enum Status: int
{
    case Ok = 200;
    case BadRequest = 400;
    case NotFound = 404;
    case InternalServerError = 500;
}
