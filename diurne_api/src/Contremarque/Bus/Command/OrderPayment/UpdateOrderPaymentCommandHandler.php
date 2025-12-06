<?php

namespace App\Contremarque\Bus\Command\OrderPayment;

use RuntimeException;
use App\Common\Bus\Command\CommandHandler;
use App\Contact\Repository\CustomerRepository;
use App\Contremarque\Repository\OrderPaymentRepository;
use App\Setting\Repository\CurrencyRepository;
use App\Setting\Repository\PaymentTypeRepository;
use App\Setting\Repository\TaxRuleRepository;
use App\User\Repository\UserRepository;

class UpdateOrderPaymentCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly OrderPaymentRepository $orderPaymentRepository,
        private readonly PaymentTypeRepository  $paymentTypeRepository,
        private readonly CustomerRepository     $customerRepository,
        private readonly UserRepository         $userRepository,
        private readonly CurrencyRepository     $currencyRepository,
        private readonly TaxRuleRepository      $taxRuleRepository,
    )
    {
    }

    public function __invoke(UpdateOrderPaymentCommand $command): OrderPaymentResponse
    {
        $orderPayment = $this->orderPaymentRepository->find($command->id);
        if (null === $orderPayment) {
            throw new RuntimeException('Order payment not found');
        }

        if (!empty($command->paymentMethodId)) {
            $paymentMethod = $this->paymentTypeRepository->find($command->paymentMethodId);
            if (null === $paymentMethod) {
                throw new RuntimeException('Payment method not found');
            }
            $orderPayment->setPaymentMethod($paymentMethod);
        }

        if ($command->customerIdProvided) {
            if (null === $command->customerId) {
                $orderPayment->setCustomer(null);
            } else {
                $customer = $this->customerRepository->find($command->customerId);
                if (null === $customer) {
                    throw new RuntimeException('Customer not found');
                }
                $orderPayment->setCustomer($customer);
            }
        }

        if ($command->commercialIdProvided) {
            if (null === $command->commercialId) {
                $orderPayment->setCommercial(null);
            } else {
                $commercial = $this->userRepository->find($command->commercialId);
                if (null === $commercial) {
                    throw new RuntimeException('Commercial (User) not found');
                }
                if ('Commercial' !== $commercial->getProfile()->getName()) {
                    throw new RuntimeException('User is not a Commercial');
                }
                $orderPayment->setCommercial($commercial);
            }
        }

        if (!empty($command->currencyId)) {
            $currency = $this->currencyRepository->find($command->currencyId);
            if (null === $currency) {
                throw new RuntimeException('Currency not found');
            }
            $orderPayment->setCurrency($currency);
        }

        if (!empty($command->taxRuleId)) {
            $taxRule = $this->taxRuleRepository->find($command->taxRuleId);
            if (null === $taxRule) {
                throw new RuntimeException('Tax rule not found');
            }
            $orderPayment->setTaxRule($taxRule);
        }

        if (!empty($command->accountLabel)) {
            $orderPayment->setAccountLabel($command->accountLabel);
        }
        if (!empty($command->transactionNumber)) {
            $orderPayment->setTransactionNumber($command->transactionNumber);
        }
        if (!empty($command->paymentAmountHt)) {
            $orderPayment->setPaymentAmountHt($command->paymentAmountHt);
        }
        if (!empty($command->paymentAmountTtc)) {
            $orderPayment->setPaymentAmountTtc($command->paymentAmountTtc);
        }

        $this->orderPaymentRepository->persist($orderPayment);
        $this->orderPaymentRepository->flush();

        return new OrderPaymentResponse($orderPayment);
    }

}
