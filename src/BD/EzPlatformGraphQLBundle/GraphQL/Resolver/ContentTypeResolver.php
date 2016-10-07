<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\EzPlatformGraphQLBundle\GraphQL\Resolver;

use eZ\Publish\API\Repository\ContentTypeService;
use Overblog\GraphQLBundle\Resolver\TypeResolver;

class ContentTypeResolver
{
    /**
     * @var \eZ\Publish\API\Repository\ContentTypeService
     */
    private $contentTypeService;

    /**
     * @var TypeResolver
     */
    private $typeResolver;

    public function __construct(TypeResolver $typeResolver, ContentTypeService $contentTypeService)
    {
        $this->typeResolver = $typeResolver;
        $this->contentTypeService = $contentTypeService;
    }

    public function resolveContentTypeById($contentTypeId)
    {
        return $this->contentTypeService->loadContentType($contentTypeId);
    }
}
