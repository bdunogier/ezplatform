<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class ConfigTreeLoader extends FileLoader
{
    /**
     * Loads a resource.
     *
     * @param mixed  $file The resource
     * @param string $type The resource type
     *
     * @throws InvalidArgumentException When ini file is not valid
     */
    public function load($file, $type = null)
    {
        $path = $this->locator->locate($file);
        $this->container->addResource(new FileResource($path));

        $result = Yaml::parse(file_get_contents($path));

        if (false === $result || array() === $result) {
            throw new InvalidArgumentException(sprintf('The "%s" file is not valid.', $file));
        }

        $pathInfo = pathinfo(
            substr(
                $file,
                strpos($file, 'app/config/ezplatform/') + strlen('app/config/ezplatform/')
            )
        );

        if ($pathInfo['dirname'] === '.') {
            $config = [
                $pathInfo['filename'] => $result,
            ];
        } else {
            $directories = explode('/', trim($pathInfo['dirname']));
            if (count($directories) != 2) {
                throw new InvalidArgumentException(sprintf('Too many directory levels in %s', $pathInfo['dirname']));
            }
            $config = [
                $directories[0] => [
                    $directories[1] => [
                        $pathInfo['filename'] => $result,
                    ]
                ]
            ];
        }

        $this->container->loadFromExtension('ezpublish', $config);
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return Boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        // @todo consider using a custom $type
        return is_string($resource) && strpos(pathinfo($resource, PATHINFO_DIRNAME), 'app/config/ezplatform') !== false;
    }
}
