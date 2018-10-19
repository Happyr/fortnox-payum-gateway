<?php
namespace Happyr\Payum\Fortnox\Action\Api;

use FAPI\Fortnox\ApiClient;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;

abstract class BaseApiAwareAction implements ActionInterface, GatewayAwareInterface, ApiAwareInterface
{
    use GatewayAwareTrait;
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = ApiClient::class;
    }
}
