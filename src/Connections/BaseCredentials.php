<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Connections;

use Igor360\NftEthPhpConnector\Interfaces\ConnectionInterface;

class BaseCredentials implements ConnectionInterface
{
    protected string $host;

    protected ?int $port;

    protected bool $ssl;

    /**
     * @param string $host
     * @param int|null $port
     * @param bool $ssl
     */
    public function __construct(string $host, ?int $port = null, bool $ssl = false)
    {
        $this->host = $host;
        $this->port = $port;
        $this->ssl = $ssl;
    }

    public function host(): string
    {
        return $this->host;
    }

    public function port(): ?int
    {
        return $this->port;
    }

    /**
     * Set ssl connection
     * @return bool
     */
    public function ssl(): bool
    {
        return (bool)$this->ssl;
    }

}