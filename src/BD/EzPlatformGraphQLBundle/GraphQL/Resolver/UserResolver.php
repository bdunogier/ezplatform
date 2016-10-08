<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\EzPlatformGraphQLBundle\GraphQL\Resolver;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\UserService;
use eZ\Publish\API\Repository\Values\Content\Content;

class UserResolver
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function resolveUser($args)
    {
        if (isset($args['id'])) {
            return $this->userService->loadUser($args['id']);
        }

        if (isset($args['email'])) {
            return $this->userService->loadUsersByEmail($args['email']);
        }

        if (isset($args['login'])) {
            return $this->userService->loadUserByLogin($args['login']);
        }
    }

    public function resolveUserById($userId)
    {
        return $this->userService->loadUser($userId);
    }

    public function resolveContentFields(Content $content, $args)
    {
        if (isset($args['identifier'])) {
            return [$content->getField($args['identifier'])];
        }
        return $content->getFieldsByLanguage();
    }
}
