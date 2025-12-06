<?php

namespace App\Contremarque\Bus\Command\OrderPayment;

use InvalidArgumentException;
use App\Common\Bus\Command\CommandHandler;
use App\Contact\Repository\CustomerRepository;
use App\Contremarque\Entity\OrderPayment\OrderPayment;
use App\Contremarque\Repository\OrderPaymentRepository;
use App\Setting\Repository\CurrencyRepository;
use App\Setting\Repository\PaymentTypeRepository;
use App\Setting\Repository\TaxRuleRepository;
use App\User\Repository\UserRepository;

class CreateOrderPaymentCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly OrderPaymentRepository $orderPaymentRepository,
        private readonly PaymentTypeRepository  $paymentTypeRepository,
        private readonly CustomerRepository     $customerRepository,
        private readonly UserRepository         $userRepository,
        private readonly CurrencyRepository     $currencyRepository,
        private readonly TaxRuleRepository      $taxRuleRepository
    )
    {
    }

    public function __invoke(CreateOrderPaymentCommand $command): OrderPaymentResponse
    {

        $paymentMethod = $this->paymentTypeRepository->find($command->getPaymentMethodId());
        if (null === $paymentMethod) {
            throw new InvalidArgumentException('Payment method not found');
        }

        $customer = null;
        if (null !== $command->getCustomerId()) {
            $customer = $this->customerRepository->find($command->getCustomerId());
            if (null === $customer) {
                throw new InvalidArgumentException('Customer not found');
            }
        }

        $commercial = null;
        if (null !== $command->getCommercialId()) {
            $commercial = $this->userRepository->find($command->getCommercialId());
            if (null === $commercial) {
                throw new InvalidArgumentException('Commercial (User) not found');
            }
            if ($commercial->getProfile()->getName() !== 'Commercial') {
                throw new InvalidArgumentException('User is not a Commercial');
            }
        }

        $currency = $this->currencyRepository->find($command->getCurrencyId());
        if (null === $currency) {
            throw new InvalidArgumentException('Currency not found');
        }

        $taxRule = $this->taxRuleRepository->find($command->getTaxRuleId());
        if (null === $taxRule) {
            throw new InvalidArgumentException('Tax rule not found');
        }


        $orderPayment = new OrderPayment();
        $orderPayment->setDateOfReceipt($command->getDateOfReceipt());
        $orderPayment->setPaymentMethod($paymentMethod);
        $orderPayment->setCustomer($customer);
        $orderPayment->setCommercial($commercial);
        $orderPayment->setCurrency($currency);
        $orderPayment->setTaxRule($taxRule);
        $orderPayment->setAccountLabel($command->getAccountLabel());
        $orderPayment->setTransactionNumber($command->getTransactionNumber());
        $orderPayment->setPaymentAmountHt($command->getPaymentAmountHt());
        $orderPayment->setPaymentAmountTtc($command->getPaymentAmountTtc());

        $this->orderPaymentRepository->persist($orderPayment);
        $this->orderPaymentRepository->flush();

        return new OrderPaymentResponse($orderPayment);

    }
}