<?php

namespace PamutProba\Entity;

abstract class Entity
{
    public static abstract function random(): Entity;
}