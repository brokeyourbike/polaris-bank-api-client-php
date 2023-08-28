<?php

// Copyright (C) 2023 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\PolarisBank\Tests;

use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\PolarisBank\Models\QueryTransactionResponse;
use BrokeYourBike\PolarisBank\Interfaces\TransactionInterface;
use BrokeYourBike\PolarisBank\Interfaces\ConfigInterface;
use BrokeYourBike\PolarisBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class QueryTransactionTest extends TestCase
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
                "message": null,
                "data": {
                    "provider_responde_code": "",
                    "charge_status": null,
                    "provider": "",
                    "errors": [
                        {
                            "code": "01",
                            "message": "Error occurred while processing request"
                        }
                    ],
                    "error": {
                        "code": "01",
                        "message": "Error occurred while processing request"
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

        $requestResult = $api->queryTransaction('req-123', $transaction);
        $this->assertInstanceOf(QueryTransactionResponse::class, $requestResult);
        $this->assertEquals('Failed', $requestResult->status);
        $this->assertNull($requestResult->message);
        $this->assertEquals('01', $requestResult->errorCode);
        $this->assertEquals('Error occurred while processing request', $requestResult->errorMessage);
    }
}