<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Services\DataTypes;

use Igor360\UniswapV2Connector\Exceptions\ContractABIException;

/**
 * @source https://github.com/furqansiddiqui/ethereum-php
 */
class Method
{
    /** @var string|null */
    public ?string $name;
    /** @var string */
    public string $type;
    /** @var bool|null */
    public ?bool $isConstant;
    /** @var bool|null */
    public ?bool $isPayable;
    /** @var array|null */
    public ?array $inputs;
    /** @var array|null */
    public ?array $outputs;


    /**
     * Method constructor.
     * @param array $method
     * @throws ContractABIException
     */
    public function __construct(array $method)
    {
        // Name
        $name = $method["name"] ?? null;
        if (!is_string($name) && !is_null($name)) { // Loosened for "constructor" and "fallback"
            throw new ContractABIException('Unexpected value for param "name"');
        }

        $this->name = $name;

        // Type
        $type = $method["type"];
        if (!is_string($type) || !in_array($type, ["function", "constructor", "fallback", "receive"])) {
            throw new ContractABIException(sprintf('Cannot create method for type "%s"', strval($type)));
        }

        $this->type = $type;
        if ($this->type === "function") {
            if (!$this->name) {
                throw new ContractABIException('ABI method type "function" requires a valid name');
            }
        }

        // Constant
        $isConstant = $method["constant"] ?? null;
        if (!is_bool($isConstant) && !is_null($isConstant)) {
            throw $this->unexpectedParamValue("constant", "bool", gettype($isConstant));
        }

        $this->isConstant = $isConstant;

        // Payable
        $isPayable = $method["payable"] ?? null;
        if (!is_bool($isPayable) && !is_null($isPayable)) {
            throw $this->unexpectedParamValue("constant", "bool", gettype($isPayable));
        }

        $this->isPayable = $isPayable;

        // Inputs
        $inputs = $method["inputs"] ?? false;
        if (!is_array($inputs)) { // Must be an Array
            if (!in_array($this->type, ["fallback", "receive"])) { // ...unless its type "fallback" or "receive"
                throw $this->unexpectedParamValue("inputs", "array");
            }
        }

        $this->inputs = [];
        if (is_array($inputs)) {
            $this->inputs = $this->params("inputs", $inputs);
        }

        // Outputs
        $this->outputs = null;
        $outputs = $method["outputs"] ?? false;
        if (is_array($outputs)) {
            $this->outputs = $this->params("outputs", $outputs, $name);
        }
    }

    /**
     * @param string $which
     * @param array $params
     * @return array
     * @throws ContractABIException
     */
    private function params(string $which, array $params, $nameMen = ''): array
    {
        $methodId = $this->name ?? $this->type;
        $result = [];

        $index = 0;
        foreach ($params as $param) {
            if (!is_array($param)) {
                throw new ContractABIException(
                    sprintf(
                        'All "%s" params for method "%s" must be type Array, got "%s" at index %d',
                        $which,
                        $methodId,
                        gettype($param),
                        $index
                    )
                );
            }

            $name = $param["name"] ?? null;
            if (!is_string($name) || !preg_match('/^\w*$/', $name)) {
                throw new ContractABIException(
                    sprintf('Bad value for param "name" of "%s" at index %d', $which, $index)
                );
            }

            $type = $param["type"] ?? null;
            if (!is_string($type) || !preg_match('/^\w+(\[\])?$/', $type)) {
                throw new ContractABIException(
                    sprintf('Bad value for param "type" of "%s" at index %d', $which, $index)
                );
            }

            if (!is_null($type) && !preg_match('/^((hash|uint|int|string)(8|16|32|64|112|128|256)?(\[\])?|bool(\[\])?|address(\[\])?|(bytes)(4|32)?|tuple(\[\])?|tuple)$/', $type)) {
                throw new ContractABIException(
                    sprintf('Invalid/unacceptable type for param "%s" in "%s"', $name, $which)
                );
            }

            $methodParam = new MethodParam();
            $methodParam->name = $name;
            $methodParam->type = $type;
            $result[] = $methodParam;
            $index++;
        }

        return $result;
    }

    /**
     * @param string $param
     * @param null|string $expected
     * @param null|string $got
     * @return ContractABIException
     */
    private function unexpectedParamValue(
        string  $param,
        ?string $expected = null,
        ?string $got = null
    ): ContractABIException
    {
        $message = sprintf('Bad/unexpected value for param "%s"', $param);
        if ($expected) {
            $message .= sprintf(', expected "%s"', $expected);
        }

        if ($got) {
            $message .= sprintf(', got "%s"', $got);
        }


        return $this->exception($message);
    }

    /**
     * @param string $message
     * @return ContractABIException
     */
    private function exception(string $message): ContractABIException
    {
        $methodName = is_string($this->name) ? $this->name : "*unnamed*";
        return new ContractABIException(
            sprintf('ABI method [%s]: %s', $methodName, $message)
        );
    }
}

