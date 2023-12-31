<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Configuration;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Cache the Credential generated by the decorated CredentialProvider in memory.
 *
 * The Credential will be reused until it expires.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class CacheProvider implements CredentialProvider, ResetInterface
{
    /**
     * @var (Credentials|null)[]
     */
    private $cache = [];

    private $decorated;

    public function __construct(CredentialProvider $decorated)
    {
        $this->decorated = $decorated;
    }

    public function getCredentials(Configuration $configuration): ?Credentials
    {
        $key = spl_object_hash($configuration);
        if (!\array_key_exists($key, $this->cache) || (null !== $this->cache[$key] && $this->cache[$key]->isExpired())) {
            $this->cache[$key] = $this->decorated->getCredentials($configuration);
        }

        return $this->cache[$key];
    }

    public function reset(): void
    {
        $this->cache = [];
    }
}
