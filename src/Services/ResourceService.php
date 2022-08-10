<?php

namespace Igor360\NftEthPhpConnector\Services;

use Igor360\NftEthPhpConnector\Interfaces\ServiceInterface;
use Igor360\NftEthPhpConnector\Resources\Resource;

abstract class ResourceService implements ServiceInterface
{
    private Resource $resource;

    /**
     * @return Resource
     */
    public function getResource(): Resource
    {
        return $this->resource;
    }

    /**
     * @param Resource $resource
     */
    public function setResource(Resource $resource): self
    {
        $this->resource = $resource;
        $this->load();
        return $this;
    }

    public function update(): ServiceInterface
    {
        $this->load();
    }

    public function data(): array
    {
        return $this->resource->data()->toArray();
    }

}