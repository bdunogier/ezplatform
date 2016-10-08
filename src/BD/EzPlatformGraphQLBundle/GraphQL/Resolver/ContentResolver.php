<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\EzPlatformGraphQLBundle\GraphQL\Resolver;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Relation;
use eZ\Publish\API\Repository\Values\Content\Search\SearchHit;

class ContentResolver
{
    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var SearchService
     */
    private $searchService;

    public function __construct(ContentService $contentService, SearchService $searchService)
    {
        $this->contentService = $contentService;
        $this->searchService = $searchService;
    }

    public function findContentByType($contentTypeId)
    {
        $searchResults = $this->searchService->findContentInfo(
            new Query([
                'filter' => new Query\Criterion\ContentTypeId($contentTypeId)
            ])
        );

        return array_map(
            function(SearchHit $searchHit) {
                return $searchHit->valueObject;
            },
            $searchResults->searchHits
        );
    }

    /**
     * @return \eZ\Publish\API\Repository\Values\Content\Relation[]
     */
    public function findContentRelations(ContentInfo $contentInfo, $version = null)
    {
        return $this->contentService->loadRelations(
            $this->contentService->loadVersionInfo($contentInfo, $version)
        );
    }

    public function findContentReverseRelations(ContentInfo $contentInfo, $version = null)
    {
        return $this->contentService->loadReverseRelations($contentInfo);
    }

    public function resolveContent($args)
    {
        if (isset($args['id'])) {
            return $this->contentService->loadContentInfo($args['id']);
        }

        if (isset($args['remoteId'])) {
            return $this->contentService->loadContentInfoByRemoteId($args['remoteId']);
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
