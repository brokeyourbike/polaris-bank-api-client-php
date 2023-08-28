<?php

// Copyright (C) 2023 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\PolarisBank\Tests;

use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\PolarisBank\Models\TransactionResponse;
use BrokeYourBike\PolarisBank\Interfaces\TransactionInterface;
use BrokeYourBike\PolarisBank\Interfaces\ConfigInterface;
use BrokeYourBike\PolarisBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class DisburseTest extends TestCase
{
    /** @test */
    public function it_can_handle_failed_response(): void
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();

        /** @var TransactionInterface $transaction */
        $this->assertInstanceOf(TransactionInterface::class, $transaction);
        
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getToken')->willReturn('john');
        $mockedConfig->method('getSecret')->willReturn('password');
        $mockedConfig->method('isMock')->willReturn(false);

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "status": "Failed",
                "message": "Unable to process transaction",
                "data": {
                    "options": null,
                    "app_info": null,
                    "provider_response_code": null,
                    "provider": null,
                    "errors": [
                        {
                            "code": "04",
                            "message": "Unable to processs transaction"
                        }
                    ],
                    "error": {
                        "code": "04",
                        "message": "Unable to processs transaction"
                    },
                    "provider_response": null,
                    "client_info": {
                        "name": null,
                        "id": null,
                        "bank_cbn_code": null,
                        "bank_name": null,
                        "console_url": null,
                        "js_background_image": null,
                        "css_url": null,
                        "logo_url": null,
                        "footer_text": null,
                        "show_options_icon": false,
                        "paginate": false,
                        "paginate_count": 0,
                        "options": null,
                        "merchant": null,
                        "colors": null,
                        "meta": null
                    }
                }
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->once()->andReturn($mockedResponse);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * */
        $api = new Client($mockedConfig, $mockedClient);

        $requestResult = $api->disburse('req-123', $transaction);
        $this->assertInstanceOf(TransactionResponse::class, $requestResult);
    }
}