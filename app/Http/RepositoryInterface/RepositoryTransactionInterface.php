<?php
namespace App\Http\RepositoryInterface;

interface RepositoryTransactionInterface{
    public function index();
    public function viewCustomerTransaction();
    public function store($request);
    public function storeTransactionDetail($request);
    public function status($request);
    public function getTotalAmount($amount,$vat,$isVatInclusive);
    public function reportMonthly();

}
