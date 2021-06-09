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
            new TwigFunction('getStatus', [$this, 'getStatus'])
        ];
    }
    /**
     * Display rating with bootstrap stars (fill and not fill)
     *
     * @param float $rating
     * @return void
     */
    public function displayStarRating(float $rating = null): void
    {

        $missingStar = 5 - $rating;

        for ($i = 1; $i <= $rating; $i++) {
            echo '<i class="bi bi-star-fill"></i>';
        }

        for ($i = 1; $i <= $missingStar; $i++) {
            echo '<i class="bi bi-star"></i>';
        }
    }

    /**
     * Function to set the path of re-sorting when we already did a sorting. Useful mostly on the admin section.
     *
     * @param string $filename
     * @param string $actualOrder
     * @param string $orderBy
     * @param string $order
     * @return void
     */
    public function setSortingPath(string $filename, string $actualOrder, string $orderBy, string $order): void
    {
        if ($actualOrder === $orderBy && $order === 'DESC') {
            echo "{{path(app_admin_" . $filename . "_sort),{'orderBy':$orderBy,'order':'ASC'}}}";
        } else {
            echo "{{path(app_admin_" . $filename . "_sort),{'orderBy':$orderBy,'order':'DESC'}}}";
        }
    }

    /**
     *  Function to set the icon when we already did a sorting. Useful mostly on the admin section.
     *
     * @param string $actualOrder
     * @param string $orderBy
     * @param string $order
     * @param string $type
     * @return void
     */
    public function setSortingIcon(string $actualOrder = null, string $orderBy = null, string $order = null, string $type = 'alpha'): void
    {
        if ($actualOrder === $orderBy && $order === 'ASC') {
            echo "bi bi-sort-$type-down";
        } else {
            echo "bi bi-sort-$type-up";
        }
    }

    /**
     * Function used to format the price (XXX XXX XXX,XX €)
     *
     * @param float $price
     * @param integer $decimals
     * @return string
     */
    public function formatPrice(float $price, int $decimals): string
    {
        $price = number_format($price, $decimals, ',', ' ');
        return $price;
    }

    /**
     * Function linked to the Trip Entity. Permit to show the status name instead of the status number.
     *
     * @param integer $status
     * @param int $tripId
     * @param boolean $reserved
     * @return void
     */
    public function getStatus(int $status, $tripId = null, $reserved = false): void
    {

        switch ($status) {
            case 1:
                echo '<p class="d-flex justify-content-end"><a href="/trips/' . $tripId . '/payment" class="btn btn-warning w-50 btn-sm text-center"> En attente de paiement </a></p>';
                break;
            case 2:
                echo '<button class="btn btn-success w-50 btn-sm text-center"> Reservé</button>';
                break;
            case 3:
                echo '<p class="bg-danger w-50 ml-auto p-1 text-center text-white"> Complet </p>';
                break;
            case 4:
                echo '<p class="bg-primary w-50 ml-auto p-1 text-center text-white"> Voyage terminé </p>';
                break;
        }
    }
}
