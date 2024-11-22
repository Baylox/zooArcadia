<?php

namespace App\Factory;

use App\Entity\Image;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Proxy;
use App\Factory\AnimalFactory;

/**
 * @extends PersistentProxyObjectFactory<Image>
 */
final class ImageFactory extends PersistentProxyObjectFactory
{
    protected function defaults(): array
    {
        return [
            'fileName' => 'image_test_' . self::faker()->unique()->numberBetween(1, 100) . '.jpg',
            'animal' => AnimalFactory::new(),
        ];
    }

    public static function class(): string
    {
        return Image::class;
    }
}

