<?php declare(strict_types=1);

namespace PamutProba\Core\App\View;

use PamutProba\Core\Utility\Url;

class RedirectView extends View
{
    public function render(): string
    {
        header("Location: {$this->data->get("location")}");
        return "";
    }

    public static function to(string $location): static
    {
        return new static([
            "location" => Url::base($location)
        ]);
    }
}