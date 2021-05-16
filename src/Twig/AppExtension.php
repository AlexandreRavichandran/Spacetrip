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
        ];
    }

    public function displayStarRating($rating)
    {

        $missingStar = 5 - $rating;

        for ($i = 1; $i <= $rating; $i++) {
            echo '<i class="bi bi-star-fill"></i>';
        }

        for ($i = 1; $i <= $missingStar; $i++) {
            echo '<i class="bi bi-star"></i>';
        }
    }
}
