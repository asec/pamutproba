<?php

namespace PamutProba\Core\Utility;

class Misc
{
    public static function diffEntityArrays(array $base, array $current): array
    {
        $result = [];
        foreach ($current as $key => $value)
        {
            if ($base[$key] !== $value)
            {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}