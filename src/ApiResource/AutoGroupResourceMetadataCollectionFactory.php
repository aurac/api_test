<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Operations;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\ResourceMetadataCollection;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\MapDecorated;

#[AsDecorator(
    'api_platform.metadata.resource.metadata_collection_factory',
)]
class AutoGroupResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    private ResourceMetadataCollectionFactoryInterface $decorated;

    public function __construct(#[MapDecorated] ResourceMetadataCollectionFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceMetadataCollection = $this->decorated->create($resourceClass);

        foreach ($resourceMetadataCollection as $i => $resourceMetadata) {
            /** @var Operations $operations */
            $operations = $resourceMetadata->getOperations();

            if (!$operations) {
                continue;
            }

            /** @var Operation $operation */
            foreach ($resourceMetadata->getOperations() as $operationName => $operation) {
                if (!$operation instanceof HttpOperation) {
                    continue;
                }

                $shortName = $resourceMetadata->getShortName();
                $isItem = !$operation instanceof CollectionOperationInterface;
                $method = $operation->getMethod();

                $normalizationContext = $operation->getNormalizationContext() ?? [];
                $normalizationGroups = $normalizationContext['groups'] ?? [];

                $normalizationGroups = array_unique(
                    array_merge(
                        $normalizationGroups,
                        $this->getDefaultGroups(
                            $shortName,
                            true,
                            $isItem,
                            $method
                        )
                    )
                );

                $normalizationContext['groups'] = $normalizationGroups;

                $operation = $operation->withNormalizationContext($normalizationContext);

                $denormalizationContext = $operation->getDenormalizationContext() ?? [];
                $denormalizationGroups = $denormalizationContext['groups'] ?? [];

                $denormalizationGroups = array_unique(
                    array_merge(
                        $denormalizationGroups,
                        $this->getDefaultGroups(
                            $shortName,
                            false,
                            $isItem,
                            $method
                        )
                    )
                );

                $denormalizationContext['groups'] = $denormalizationGroups;


                $operation = $operation->withDenormalizationContext($denormalizationContext);

                $operations->add($operationName, $operation);
            }

            $resourceMetadataCollection[$i] = $resourceMetadata->withOperations($operations);
        }

        return $resourceMetadataCollection;
    }

    private function getDefaultGroups(string $shortName, bool $normalization, bool $isItem, string $operationName): array
    {
        $shortName = strtolower($shortName);
        $readOrWrite = $normalization ? 'read' : 'write';
        $itemOrCollection = $isItem ? 'item' : 'collection';
        $operationName = strtolower($operationName);

        return [
            // {shortName}:{read/write}
            // e.g. foo:read
            sprintf('%s:%s', $shortName, $readOrWrite),
            // {shortName}:{item/collection}:{read/write}
            // e.g. foo:collection:read
            sprintf('%s:%s:%s', $shortName, $itemOrCollection, $readOrWrite),
            // {shortName}:{item/collection}:{operationName}
            // e.g. foo:collection:get
            sprintf('%s:%s:%s', $shortName, $itemOrCollection, $operationName),
        ];
    }
}
