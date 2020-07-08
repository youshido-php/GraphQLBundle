<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType\mimeType;

/**
 * Class ImageMimeType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType\mimeType
 * @author mirkl
 */
class ImageMimeType extends MimeType
{
    /** @var string[] */
    const IMAGE_MIME_TYPES = [
        MimeType::IMAGE_PNG,
        MimeType::IMAGE_JPEG,
        MimeType::IMAGE_GIF
    ];

    /**
     * @return bool|string
     */
    public function getName()
    {
        return 'ImageMimeType';
    }

    /**
     * @return string[]
     */
    protected function getAvailableMimeTypes(): array
    {
        return self::IMAGE_MIME_TYPES;
    }

}
