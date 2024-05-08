<?php

namespace PamutProba\Http;

enum Status: int
{
    case Ok = 200;
    case NotFound = 404;
    case InternalServerError = 500;
}
