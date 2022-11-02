<?php

namespace Igor360\NftEthPhpConnector\Contracts;

use Igor360\NftEthPhpConnector\Services\ContractService;
use Illuminate\Support\Arr;

abstract class Contract extends ContractService
{
    public function __call($name, $arguments)
    {
        switch ($name) {
            case str_contains($name, 'encode_'):
                $functionName = str_replace('encode_', '', $name);
                return $this->encodeCall($functionName, $arguments);
            case str_contains($name, 'encode'):
                $functionName = str_replace('encode', '', $name);
                $functionName = lcfirst($functionName);
                return $this->encodeCall($functionName, $arguments);
            case str_contains($name, 'call_'):
                $functionName = str_replace('call_', '', $name);
                return $this->call($functionName, $arguments);
            case str_contains($name, 'call'):
                $functionName = str_replace('call', '', $name);
                $functionName = lcfirst($functionName);
                return $this->call($functionName, $arguments);
            default:
                return $name(...$arguments);
        }
    }

    private function call(string $functionName, $args)
    {
        $contractAddress = array_shift($args);
        $res = $this->callContractFunction($contractAddress, $functionName, $args);
        return Arr::first($res);
    }

    private function encodeCall(string $functionName, $args)
    {
        return $this->ABIService->encodeCall($functionName, $args);
    }
}