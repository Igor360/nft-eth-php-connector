<?php

namespace Igor360\NftEthPhpConnector\Interfaces;

interface ModelInterface
{
    /**
     * @description Convert model to json
     * @return string
     */
    public function toJson(): string;

    /**
     * @description Convert model to object
     * @return object
     */
    public function toObject(): object;

    /**
     * @description Convert model to array
     * @return array
     */
    public function toArray(): array;

    /**
     * @description It uses in ResourceFactory for call contract functions
     * Format:
     *  'model_variable_name' => 'contract_function_name'
     * @return array
     */
    public function mapFrom(): array;
}