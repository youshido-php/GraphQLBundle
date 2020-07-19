<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ScalarType\mimeType;

use Youshido\GraphQL\Type\Scalar\AbstractScalarType;

/**
 * Class MimeType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType\mimeType
 * @author mirkl
 */
class MimeType extends AbstractScalarType
{
    const IMAGE_PNG = 'image/png';
    const IMAGE_JPEG = 'image/jpeg';
    const IMAGE_GIF = 'image/gif';

    const MIME_TYPES = [self::IMAGE_PNG, self::IMAGE_JPEG, self::IMAGE_GIF];

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Represents browser MIME type';
    }

    /**
     * @return false|String
     */
    public function getName()
    {
        return 'MimeType';
    }

    /**
     * Checks whether value is one of available mime types
     *
     * @param $value
     *
     * @return bool
     */
    public function isValidValue($value)
    {
        if ($value === null) {
            return true; // supports null value
        }

        // mime type can contain multiple parts
        $parts = array_map(function ($v) {
            return trim($v);
        },
            explode(';', $value)
        );

        foreach ($parts as $part) {
            if (in_array($part, $this->getAvailableMimeTypes())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    protected function getAvailableMimeTypes(): array
    {
        return self::MIME_TYPES;
    }
}
