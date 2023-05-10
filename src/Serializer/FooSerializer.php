<?php

namespace App\Serializer;

use App\Entity\Foo;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class FooSerializer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'FOO_NORMALIZER_ALREADY_CALLED';

    public function normalize($object, $format = null, array $context = []): array
    {
       /** @var Foo $object */
        if ($object->getType()) {
            $context['groups'][] = $object->getType();
        }

        $context[self::ALREADY_CALLED] = true;

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Foo;
    }
}
