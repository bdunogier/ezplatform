<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\EzPlatformGraphQLBundle\GraphQL\Resolver;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Values\Content\Content;

class ContentResolver
{
    /**
     * @var ContentService
     */
    private $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function resolveContent($args)
    {
        if (isset($args['id'])) {
            if (isset($args['version'])) {
                return $this->contentService->loadContentByVersionInfo(
                    $this->contentService->loadVersionInfo(
                        $this->contentService->loadContentInfo($args['id']),
                        $args['version']
                    )
                );

            } else {
                return $this->contentService->loadContent($args['id']);
            }
        }
    }

    public function resolveContentById($contentId)
    {
        return $this->contentService->loadContent($contentId);
    }

    public function resolveContentFields(Content $content, $args)
    {
        if (isset($args['identifier'])) {
            return [$content->getField($args['identifier'])];
        }
        return $content->getFieldsByLanguage();
    }
}
