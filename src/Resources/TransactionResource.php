<?php declare(strict_types=1);

namespace Igor360\NftEthPhpConnector\Resources;

use Igor360\NftEthPhpConnector\Contracts\ContractsFactory;
use Igor360\NftEthPhpConnector\Contracts\ERC20Contract;
use Igor360\NftEthPhpConnector\Exceptions\InvalidAddressException;
use Igor360\NftEthPhpConnector\Exceptions\TransactionException;
use Igor360\NftEthPhpConnector\Interfaces\ConnectionInterface;
use Igor360\NftEthPhpConnector\Models\Transaction;
use Igor360\NftEthPhpConnector\Services\EthereumService;
use Illuminate\Support\Arr;

class TransactionResource extends Resource
{
    private ConnectionInterface $credentials;

    public function __construct(?string $hash, ConnectionInterface $credentials)
    {
        $this->credentials = $credentials;
        $this->service = new EthereumService($credentials);
        $this->setAddressOrHash($hash);
    }

    public function load(): self
    {
        $this->loadTransaction();
        $this->loadLogs();
        $this->loadCoin();
        return $this;
    }

    private function loadTransaction(): void
    {
        $transaction = $this->service->getTransactionByHash($this->getAddressOrHash());
        if (is_null($transaction)) {
            throw new TransactionException("Transaction not found");
        }

        // Parse data
        $this->model->hash = Arr::get($transaction, 'hash');
        $this->model->from = Arr::get($transaction, 'from');
        $this->model->to = Arr::get($transaction, 'to');
        $this->model->value = (string)hexdec(Arr::get($transaction, 'value'));
        $this->model->data = Arr::get($transaction, 'input');
        $this->model->gas = (string)hexdec(Arr::get($transaction, 'gas'));
        $this->model->gasPrice = (string)hexdec(Arr::get($transaction, 'gasPrice'));
        $this->model->block = (string)hexdec(Arr::get($transaction, 'blockNumber'));
        $this->model->blockHash = (string)Arr::get($transaction, 'blockHash');
        $this->model->nonce = (string)hexdec(Arr::get($transaction, 'nonce'));
        $this->model->r = Arr::get($transaction, 'r');
        $this->model->s = Arr::get($transaction, 's');
        $this->model->v = Arr::get($transaction, 'v');
    }

    private function loadLogs(): void
    {
        $transaction = $this->service->getTransactionReceipt($this->getAddressOrHash());
        if (is_null($transaction)) {
            throw new TransactionException("Transaction logs not found");
        }

        // Parse data
        $this->model->status = (bool)hexdec(Arr::get($transaction, "status", "0x0"));
        $this->model->logsBloom = Arr::get($transaction, "logsBloom");
        $this->model->cumulativeGasUsed = (string)hexdec(Arr::get($transaction, "cumulativeGasUsed") ?? "");
        $this->model->gasUsed = (string)hexdec(Arr::get($transaction, "gasUsed") ?? "");
        $this->model->logs = Arr::get($transaction, "logs");
        $this->model->transactionIndex = (string)hexdec(Arr::get($transaction, "transactionIndex") ?? "");
        $this->model->type = Arr::get($transaction, "type");
        $this->model->contractAddress = Arr::get($transaction, "contractAddress");
        $this->model->token = Arr::get($transaction, "token");
    }

    public function model(): string
    {
        return Transaction::class;
    }

    public function validateAddressOrHash(): void
    {
        if (!preg_match(EthereumService::ETHEREUM_HASH, $this->getAddressOrHash())) {
            throw new InvalidAddressException();
        }
    }

    public function loadCoin(): void
    {
        $id = $this->service->getChainId();
        switch ($id) {
            case 4286:
                $coin = 'GATO';
                break;
            case 137:
                $coin = 'MATIC';
                break;
            case 97:
            case 56:
                $coin = 'BNB';
                break;
            default:
                $coin = 'ETH';
                break;
        }

        if ($this->model->token && $this->model->token != "0x0000000000000000000000000000000000000000") {
            $erc20Service = new ERC20Contract($this->credentials);
            $coin = $erc20Service->symbol($this->model->token);
        }

        $this->model->coin = $coin;
    }
}