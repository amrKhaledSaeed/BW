<?php

namespace App\Console\Commands;

use App\Http\RepositoryInterface\RepositoryTransactionInterface;
use Illuminate\Console\Command;

class UpdateTransactionStatus extends Command
{
    private $transactionInterface;

    public function __construct(RepositoryTransactionInterface $transactionInterface)
    {
        parent::__construct();
        $this->transactionInterface = $transactionInterface;

    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:updateStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $transactionsData = $this->transactionInterface->index();
       foreach($transactionsData as $transaction)
       {
        $transactionsData = $this->transactionInterface->status($transaction);
       }

    }
}
