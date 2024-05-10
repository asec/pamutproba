<?php

namespace PamutProba\Utility\Development;

class DevelopmentService implements IDevelopmentService
{

    public function isDev(): bool
    {
        return true;
    }

    public function printTrace(array $trace): void
    {
        static $index = 0;

        if (count($trace) <= 0)
        {
            return;
        }
        echo '<ul class="list-group px-2 pt-3">';
        foreach ($trace as $error)
        {
            $index++;
            echo '<li class="list-group-item">';
            echo "<div><small>{$error["file"]} (Sor: {$error["line"]})</small></div>";
            if (isset($error["function"]))
            {
                echo '<a class="badge text-bg-light text-decoration-none" data-bs-toggle="collapse" href="#stack-trace-' . $index . '">';
                echo $error["class"] ?? "";
                echo $error["type"] ?? "";
                $args = [];
                foreach ($error["args"] as $arg)
                {
                    if ($arg instanceof \Exception)
                    {
                        $args[] = " . . . ";
                        continue;
                    }
                    else if ($arg instanceof \UnitEnum)
                    {
                        $arg = get_class($arg) . "::" . $arg->name;
                    }
                    else if (is_object($arg))
                    {
                        $arg = get_class($arg);
                    }
                    else if (is_array($arg))
                    {
                        $arg = "Array";
                    }
                    $args[] = $arg;
                }
                echo "{$error["function"]}(" . implode(", ", $args) . ")";
                echo '</a>';
            }
            foreach ($error["args"] as $arg)
            {
                if ($arg instanceof \Exception)
                {
                    echo '<div class="collapse" id="stack-trace-' . $index . '">';
                    static::printTrace($arg->getTrace());
                    echo '</div>';
                }
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}