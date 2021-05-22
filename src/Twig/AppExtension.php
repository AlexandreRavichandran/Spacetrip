<?php

namespace App\Twig;

use PhpParser\Node\Stmt\Echo_;
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
            new TwigFunction('formatPrice', [$this, 'formatPrice']),
        ];
    }

    public function displayStarRating(float $rating): void
    {

        $missingStar = 5 - $rating;

        for ($i = 1; $i <= $rating; $i++) {
            echo '<i class="bi bi-star-fill"></i>';
        }

        for ($i = 1; $i <= $missingStar; $i++) {
            echo '<i class="bi bi-star"></i>';
        }
    }
    public function setSortingPath(string $filename, string $actualOrder, string $orderBy, string $order): void
    {
        if ($actualOrder === $orderBy && $order === 'DESC') {
            echo "{{path(app_admin_" . $filename . "_sort),{'orderBy':$orderBy,'order':'ASC'}}}";
        } else {
            echo "{{path(app_admin_" . $filename . "_sort),{'orderBy':$orderBy,'order':'DESC'}}}";
        }
    }

    public function setSortingIcon(string $actualOrder, string $orderBy, string $order, string $type = 'alpha'): void
    {
        if ($actualOrder === $orderBy && $order === 'ASC') {
            echo "bi bi-sort-$type-down";
        } else {
            echo "bi bi-sort-$type-up";
        }
    }

    public function formatPrice(float $price, int $decimals): string
    {
        $price = number_format($price, $decimals, ',', ' ');
        return $price;
    }
}
