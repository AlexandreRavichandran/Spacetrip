<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('displayStarRating', [$this, 'displayStarRating']),
            new TwigFunction('setSortingPath', [$this, 'setSortingPath']),
            new TwigFunction('setSortingIcon', [$this, 'setSortingIcon']),
        ];
    }

    public function displayStarRating($rating): void
    {

        $missingStar = 5 - $rating;

        for ($i = 1; $i <= $rating; $i++) {
            echo '<i class="bi bi-star-fill"></i>';
        }

        for ($i = 1; $i <= $missingStar; $i++) {
            echo '<i class="bi bi-star"></i>';
        }
    }
    public function setSortingPath($filename, $actualOrder, $orderBy, $order): void
    {
        if ($actualOrder === $orderBy && $order === 'DESC') {
            echo "{{path(app_admin_" . $filename . "_sort),{'orderBy':$orderBy,'order':'ASC'}}}";
        } else {
            echo "{{path(app_admin_" . $filename . "_sort),{'orderBy':$orderBy,'order':'DESC'}}}";
        }
    }

    public function setSortingIcon($actualOrder, $orderBy, $order, $type = 'alpha'): void
    {
        if ($actualOrder === $orderBy && $order === 'ASC') {
            echo "bi bi-sort-$type-down";
        } else {
            echo "bi bi-sort-$type-up";
        }
    }
}
