<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\EzPlatformGraphQLBundle\GraphQL\Resolver;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;

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
            return $this->contentService->loadContentInfo($args['id']);
        }
    }

    public function resolveContentById($contentId)
    {
        return $this->contentService->loadContentInfo($contentId);
    }

    public function resolveContentFields($contentId, $args)
    {
        $content = $this->contentService->loadContent(
            $contentId,
            isset($args['languages']) ? $args['languages'] : null,
            isset($args['version']) ? $args['version'] : null,
            isset($args['useAlwaysAvailable']) ? $args['useAlwaysAvailable'] : true
        );

        if (isset($args['identifier'])) {
            return [$content->getField($args['identifier'])];
        }

        return $content->getFieldsByLanguage();
    }

    public function resolveContentFieldsInVersion($contentId, $versionNo, $args)
    {
        return $this->resolveContentFields(
            $contentId,
            ['version' => $versionNo] + $args->getRawArguments()
        );
    }

    public function resolveContentVersions($contentId)
    {
        return $this->contentService->loadVersions(
            $this->contentService->loadContentInfo($contentId)
        );
    }
}
