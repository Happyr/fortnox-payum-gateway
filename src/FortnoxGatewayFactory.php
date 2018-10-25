<?php
namespace Happyr\Payum\Fortnox;

use FAPI\Fortnox\HttpClientConfigurator;
use FAPI\Fortnox\Hydrator\ModelHydrator;
use FAPI\Fortnox\RequestBuilder;
use Happyr\Payum\Fortnox\Action\AuthorizeAction;
use Happyr\Payum\Fortnox\Action\CancelAction;
use Happyr\Payum\Fortnox\Action\ConvertPaymentAction;
use Happyr\Payum\Fortnox\Action\CaptureAction;
use Happyr\Payum\Fortnox\Action\NotifyAction;
use Happyr\Payum\Fortnox\Action\RefundAction;
use Happyr\Payum\Fortnox\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;
use FAPI\Fortnox\ApiClient;

class FortnoxGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'fortnox',
            'payum.factory_title' => 'Fortnox',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = array(
                'fortnox.endpoint' => 'https://api.fortnox.se',
                'fortnox.client_id' => null,
            );
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [
                'fortnox.client_secret',
                'fortnox.access_token',
            ];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                $httpClientConfigurator = new HttpClientConfigurator(
                    null, //$config['payum.http_client'],
                    new ModelHydrator(),
                    new RequestBuilder($config['httplug.message_factory'])
                );

                $httpClientConfigurator
                    ->setClientSecret($config['fortnox.client_secret'])
                    ->setAccessToken($config['fortnox.access_token'])
                    ->setEndpoint($config['fortnox.endpoint'])
                    ->setClientId($config['fortnox.client_id']);

                return ApiClient::configure($httpClientConfigurator);
            };
        }
    }
}
