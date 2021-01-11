<?php

namespace Redsys\Tpv;

class StatusCodes
{
    protected static $SUCCESS = [
        '0' => [
            'title' => 'TRANSACTION APPROVED',
            'description' => 'Transaction authorised by card issuing bank'],
        '1' => [
            'title' => 'TRANSACTION APPROVED AFTER IDENTIFICATION OF HOLDER',
            'description' => 'Exclusive code for transactions Verifed by Visa or MasterCard SecureCode. The transaction has been authorised and the issuing bank informs us that it has correctly authenticated the identity of the cardholder.'],
    ];

    protected static $ERROR = [
        '101' => [
            'title' => 'EXPIRED CARD',
            'description' => 'Transaction rejected because card expiry date entered during payment is prior to that currently valid.'],
        '102' => [
            'title' => 'CARD TEMPORARILY BLOCKED OR UNDER SUSPICION OF FRAUD',
            'description' => 'Card temporarily blocked by issuing bank or under suspicion of fraud'],
        '104' => [
            'title' => 'OPERATION NOT ALLOWED',
            'description' => 'Operation not allowed for this type of card.'],
        '106' => [
            'title' => 'NO. ATTEMPTS EXCEEDED',
            'description' => 'Number of attempts with erroneous PIN exceeded.'],
        '107' => [
            'title' => 'CONTACT ISSUER',
            'description' => 'Issuing bank does not allow automatic authorisation. It is necessary to call your authorisation centre to obtain manual approval.'],
        '109' => [
            'title' => 'IDENTIFICATION OF MERCHANT OR TERMINAL INVALID',
            'description' => 'Rejected because merchant is not correctly registered in international card systems.'],
        '110' => [
            'title' => 'AMOUNT INVALID',
            'description' => 'Transaction amount unusual for this type of merchant requesting payment authorisation.'],
        '114' => [
            'title' => 'CARD DOES NOT SUPPORT TYPE OF OPERATION REQUESTED',
            'description' => 'Operation not allowed for this type of card.'],
        '116' => [
            'title' => 'INSUFFICIENT BALANCE',
            'description' => 'The cardholder has insuf cient credit to meet pay- ment.'],
        '118' => [
            'title' => 'CARD NOT REGISTERED',
            'description' => 'Card inexistent or not registered by issuing bank.'],
        '125' => [
            'title' => 'CARD NOT EFFECTIVE',
            'description' => 'Card inexistent or not registered by issuing bank.'],
        '129' => [
            'title' => 'CVV2/CVC2 ERROR.',
            'description' => 'The CVV2/CVC2 code (three digits on back of card) entered by consumer is erroneous.'],
        '167' => [
            'title' => 'CONTACT ISSUER SUSPECTED FRAUD',
            'description' => 'Due to suspicion that transaction is fraudulent the issuing bank does not allow automatic authorisation. It is necessary to call your authorisation centre to obtain manual approval.'],
        '180' => [
            'title' => 'NON-SERVICE CARD',
            'description' => 'Operation not allowed for this type of card.'],
        '181' => [
            'title' => 'CARD WITH DEBIT OR CREDIT RESTRICTIONS',
            'description' => 'Card temporarily blocked by issuing bank'],
        '182' => [
            'title' => 'CARD WITH DEBIT OR CREDIT RESTRICTIONS',
            'description' => 'Card temporarily blocked by issuing bank'],
        '184' => [
            'title' => 'AUTHENTICATION ERROR',
            'description' => 'Exclusive code for transactions Verifed by Visa or MasterCard SecureCode. Transaction rejected because issuing bank cannot authenticate the cardholder.'],
        '190' => [
            'title' => 'REJECTION WITHOUT SPECIFYING MOTIVE',
            'description' => 'Transaction rejected by issuing bank but without reporting the reason.'],
        '191' => [
            'title' => 'ERRONEOUS EXPIRY DATE',
            'description' => 'Transaction rejected because card expiry date entered during payment does not match that currently valid.'],
        '201' => [
            'title' => 'EXPIRED CARD',
            'description' => 'Transaction rejected because card expiry date entered during payment is prior to that currently valid. In addition, the issuing bank considers that the card is subject to possible fraud.'],
        '202' => [
            'title' => 'CARD TEMPORARILY BLOCKED OR UNDER SUSPICION OF FRAUD',
            'description' => 'Card temporarily blocked by issuing bank or under suspicion of fraud. In addition, the issuing bank considers that the card is subject to possible fraud.'],
        '204' => [
            'title' => 'OPERATION NOT ALLOWED',
            'description' => 'Operation not allowed for this type of card. In addition, the issuing bank considers that the card is subject to possible fraud.'],
        '207' => [
            'title' => 'CONTACT ISSUER',
            'description' => 'Issuing bank does not allow automatic authorisation. It is necessary to call your authorisation centre to obtain manual approval. In addition, the issuing bank considers that the card is subject to possible fraud.'],
        '208' => [
            'title' => 'CARD LOST OR STOLEN',
            'description' => 'Card blocked by issuing bank as holder has reported it is stolen or lost. In addition, the issuing bank considers that the card is subject to possible fraud.'],
        '209' => [
            'title' => 'CARD LOST OR STOLEN',
            'description' => 'Card blocked by issuing bank as holder has reported it is stolen or lost. In addition, the issuing bank considers that the card is subject to possible fraud.'],
        '280' => [
            'title' => 'CVV2/CVC2 ERROR',
            'description' => 'Exclusive code for transactions in which 3-digit CVV2 code is requested (Visa card) or CVC2 (MasterCard) on back of card. The CVV2/CVC2 code entered by purchaser is erro- neous. In addition, the issuing bank considers that the card is subject to possible fraud.'],
        '290' => [
            'title' => 'REJECTION WITHOUT SPECIFYING MOTIVE',
            'description' => 'Transaction rejected by issuing bank but without repor- ting the reason. In addition, the issuing bank considers that the card is subject to possible fraud.'],
        '400' => [
            'title' => 'CANCELLATION ACCEPTED',
            'description' => 'Cancellation or partial chargeback transaction accep- ted by issuing bank.'],
        '480' => [
            'title' => 'ORIGINAL OPERATION NOT FOUND OR TIMED OUT',
            'description' => 'The cancellation or partial chargeback not accepted because original operation not located or because issuing bank has not responded within prede ned time-out limit.'],
        '481' => [
            'title' => 'CANCELLATION ACCEPTED',
            'description' => 'Cancellation or partial chargeback transaction accep- ted by issuing bank. However, issuing bank response received late, outside prede ned time-out limit.'],
        '500' => [
            'title' => 'RECONCILIATION ACCEPTED',
            'description' => 'Reconciliation transaction accepted by issuing bank.'],
        '501' => [
            'title' => 'ORIGINAL OPERATION NOT FOUND OR TIME-OUT EXCEEDED',
            'description' => 'The reconciliation was not accepted because original operation not located or because issuing bank has not responded within prede ned time-out limit.'],
        '502' => [
            'title' => 'ORIGINAL OPERATION NOT FOUND OR TIME-OUT EXCEEDED',
            'description' => 'The reconciliation was not accepted because original operation not located or because issuing bank has not responded within prede ned time-out limit.'],
        '503' => [
            'title' => 'ORIGINAL OPERATION NOT FOUND OR TIME-OUT EXCEEDED',
            'description' => 'The reconciliation was not accepted because original operation not located or because issuing bank has not responded within prede ned time-out limit.'],
        '9928' => [
            'title' => 'CANCELLATION OF PRE—AUTHORISATION PERFORMED BY SYSTEM',
            'description' => 'System has cancelled deferred pre-authorisation as over 72 hours have passed.'],
        '9929' => [
            'title' => 'CANCELLATION OF PRE-AUTHORISATION PERFORMED BY MERCHANT',
            'description' => 'The cancellation of the pre-authorisation was accepted'],
        '904' => [
            'title' => 'MERCHANT NOT REGISTERED IN FUC FILE (MID FILE)',
            'description' => 'There is a problem in con guration of merchant code. Contact Banco Sabadell to solve it.'],
        '909' => [
            'title' => 'SYSTEM ERROR',
            'description' => 'Error in stability of Banco Sabadell payment gateway or exchange systems of Visa or MasterCard.'],
        '912' => [
            'title' => 'ISSUER NOT AVAILABLE',
            'description' => 'Authorising centre of issuing bank not operational at this time.'],
        '913' => [
            'title' => 'DUPLICATED TRANSMISSION',
            'description' => 'A transaction with the same order number was recently processed (Ds_Merchant_Order).'],
        '916' => [
            'title' => 'AMOUNT TOO SMALL',
            'description' => 'Not possible to operate with this amount.'],
        '928' => [
            'title' => 'TIME-OUT EXCEEDED',
            'description' => 'Issuing bank does not respond to authorisation request within prede ned time-out.'],
        '940' => [
            'title' => 'TRANSACTION CANCELLED EARLIER',
            'description' => 'Cancellation or partial chargeback of a transaction requested which was already cancelled.'],
        '941' => [
            'title' => 'AUTHORISATION TRANSACTION ALREADY CANCELLED BY PREVIOUS CANCELLATION',
            'description' => 'Con rmation of a transaction is being requested with an order number (Ds_Merchant_Order) which matches an operation already cancelled.'],
        '942' => [
            'title' => 'ORIGINAL AUTHORISATION TRANSACTION REJECTED',
            'description' => 'Con rmation of a transaction is being requested with an order number (Ds_Merchant_Order) which matches an operation already rejected.'],
        '943' => [
            'title' => 'DIFFERENT ORIGINAL TRANSACTION DATA',
            'description' => 'An erroneous confrmation is being requested.'],
        '944' => [
            'title' => 'ERRONEOUS SESSION',
            'description' => 'A third session is being requested. In the payment process only two sessions may be open (the current one and previous pending closure).'],
        '945' => [
            'title' => 'DUPLICATED TRANSMISSION',
            'description' => 'A transaction with the same order number was recently processed (Ds_Merchant_Order).'],
        '946' => [
            'title' => 'OPERATION TO BE CANCELLED IN PROGRESS',
            'description' => 'Cancellation or partial chargeback of an original transaction is requested which is still in progress and pending response.'],
        '947' => [
            'title' => 'DUPLICATED TRANSMISSION IN PROGRESS',
            'description' => 'A transaction with the same order number is being attempted (Ds_Merchant_Order) of another still pending response.'],
        '949' => [
            'title' => 'TERMINAL NON-OPERATIONAL',
            'description' => 'The merchant number (Ds_Merchant_MerchantCode) or terminal (Ds_Merchant_Terminal) are not registered or not operational.'],
        '950' => [
            'title' => 'REFUND NOT ALLOWED',
            'description' => 'Refund not allowed by regulation.'],
        '965' => [
            'title' => 'COMPLIANCE INFRINGEMENT',
            'description' => 'Infringement of Visa or Mastercard compliance'],
        '9064' => [
            'title' => 'CARD LENGTH INCORRECT',
            'description' => 'No. positions of card incorrect'],
        '9078' => [
            'title' => 'NO PAYMENT METHOD EXISTS',
            'description' => 'The types of payment de ned for the terminal (Ds_ Merchant_Terminal) by the transaction processor do not allow payment with the type of card entered.'],
        '9093' => [
            'title' => 'CARD DOES NOT EXIST',
            'description' => 'Inexistent card'],
        '9094' => [
            'title' => 'REJECTION OF ISSUERS',
            'description' => 'Operation rejected by international issuers'],
        '9104' => [
            'title' => 'SECURE OPER. NOT POSSIBLE',
            'description' => 'Merchant with obligatory authentication and holder without secure purchase code'],
        '9142' => [
            'title' => 'PAYMENT TIME LIMIT EXCEEDED',
            'description' => 'The cardholder not authenticated during maximum time allowed.'],
        '9218' => [
            'title' => 'SECURE OPERATIONS CANNOT BE PERFORMED',
            'description' => 'The Operations input does not allow Secure operations'],
        '9253' => [
            'title' => 'CHECK-DIGIT ERRONEOUS',
            'description' => 'Card does not comply with check-digit (position 16 of card number calculated using Luhn algorithm).'],
        '9256' => [
            'title' => 'PRE-AUTHORISATIONS NOT ENABLED',
            'description' => 'Card cannot perform Pre-authorisations'],
        '9261' => [
            'title' => 'OPERATING LIMIT EXCEEDED',
            'description' => 'Transaction exceeds operating limit set by Banco Sabadell'],
        '9281' => [
            'title' => 'EXCEEDS BLOCKING ALERTS',
            'description' => 'The operation exceeds the blocking alerts; cannot be processed'],
        '9283' => [
            'title' => 'EXCEEDS BLOCKING ALERTS',
            'description' => 'The operation exceeds the blocking alerts; cannot be processed'],
        '9912' => [
            'title' => 'ISSUER NOT AVAILABLE',
            'description' => 'The issuing bank’s authorization center is not operational at this time.'],
        '9913' => [
            'title' => 'ERROR in Confirmation',
            'description' => 'Error in merchant’s con rmation sent to the Virtual POS (only applicable in SOAP synchronization option)'],
        '9914' => [
            'title' => 'CONFIRM “KO”',
            'description' => 'Merchant’s KO Con rmation (only applicable to SOAP sync option)'],
        '9915' => [
            'title' => 'PAYMENT CANCELLED',
            'description' => 'The user has canceled the payment'],
        '9997' => [
            'title' => 'SIMULTANEOUS TRANSACTION',
            'description' => 'The Virtual POS is simultaneously processing another operation with the same card.'],
        '9998' => [
            'title' => 'OPERATION STATUS REQUESTED',
            'description' => 'Temporary status while operation is processed. When the operation ends this code will change.'],
        '9999' => [
            'title' => 'OPERATION STATUS AUTHENTICATING',
            'description' => 'Temporary status while POS authenticates holder. Once this process has  nalised, the POS will assign a new code to the operation.'],
    ];

    public static function getError(): array
    {
        return self::$ERROR;
    }

    public static function getSuccess(): array
    {
        return self::$SUCCESS;
    }

    public static function getMessage($code): ?array
    {
        $messages = self::$SUCCESS + self::$ERROR;
        return isset($messages[$code]) ? $messages[$code] : null;
    }
}
