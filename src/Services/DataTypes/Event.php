<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Services\DataTypes;

use Igor360\UniswapV2Connector\Exceptions\ContractABIException;

final class Event
{
    protected $abi;
    protected $anonymous;
    protected $inputs;
    protected $name;
    protected $topicInputs;
    protected $dataInputs;

    public function __construct(array $method)
    {
        $this->abi = $method;
        // Name
        $name = $method["name"] ?? null;
        if (!is_string($name) && !is_null($name)) { // Loosened for "constructor" and "fallback"
            throw new ContractABIException('Unexpected value for param "name"');
        }

        $this->name = $name;

        // Inputs
        $inputs = $method["inputs"] ?? false;

        if (!is_array($inputs) && !is_null($inputs)) {
            throw new ContractABIException('Unexpected value for param "inputs"');
        }
        $this->inputs = $inputs;

        foreach ($inputs as $input) {
            if ($input["indexed"] ?? false) {
                $this->topicInputs[] = $input;
            } else {
                $this->dataInputs[] = $input;
            }
        }

        // Anonymous
        $anonymous = $method['anonymous'] ?? false;
        if (!is_bool($anonymous) && !is_null($anonymous)) {
            throw new ContractABIException('Unexpected value for param "anonymous"');
        }

        $this->anonymous = $anonymous;
    }

    public function getSignature()
    {
        $sign = $this->name . '(';
        foreach ($this->inputs as $i => $item) {
            $item = (object)$item;
            $sign .= $item->type;
            if ($i < count($this->inputs) - 1) {
                $sign .= ',';
            }
        }
        $sign .= ')';
        return $sign;
    }

    /**
     * @return string
     */
    public function getTopic()
    {
        return '0x' . Keccak::hash($this->getSignature(), 256);
    }

    /**
     * @return string
     */
    public function getHandler()
    {
        return 'on' . ucfirst($this->name);
    }

    /**
     * @return array
     */
    public function getAbi()
    {
        return $this->abi;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getTopicInputs(): ?array
    {
        return $this->topicInputs;
    }

    /**
     * @return array
     */
    public function getDataInputs(): array
    {
        return $this->dataInputs;
    }
}
