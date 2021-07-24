<?php

namespace App\Service;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class ImageNamer implements NamerInterface
{
    /**
     * Creates a name for the file being uploaded.
     *
     * @param  object          $obj     The object the upload is attached to.
     * @param  PropertyMapping $mapping The mapping to use to manipulate the given object.
     * @return string          The file name.
     */
    public function name($object, PropertyMapping $mapping): string
    {
        $objectName = $object->getName();

        $name = 'picture_' . $objectName . '.' . $object->getImageFile()->guessExtension();

        return $name;
    }
}
