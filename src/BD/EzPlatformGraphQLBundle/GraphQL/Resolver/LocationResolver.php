<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\EzPlatformGraphQLBundle\GraphQL\Resolver;

use eZ\Publish\API\Repository\LocationService;
use Overblog\GraphQLBundle\Resolver\TypeResolver;

class LocationResolver
{
    /**
     * @var \eZ\Publish\API\Repository\LocationService
     */
    private $locationService;
    /**
     * @var TypeResolver
     */
    private $typeResolver;

    public function __construct(TypeResolver $typeResolver, LocationService $locationService)
    {
        $this->locationService = $locationService;
        $this->typeResolver = $typeResolver;
    }

    public function resolveLocation($args)
    {
        if (isset($args['id'])) {
            return $this->locationService->loadLocation($args['id']);
        }
    }
}
