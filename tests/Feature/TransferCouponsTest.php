<?php

namespace Tests\Feature;

use App\Models\TransferCoupon;
use Tests\ControllerTest;

class TransferCouponsTest extends ControllerTest
{
    protected string $model = TransferCoupon::class;
    protected string $routeName = 'coupons.transfer';
    protected string $varName = 'transferCoupon';
    protected array $methods = ['show' => 302, 'create' => 302];

}
