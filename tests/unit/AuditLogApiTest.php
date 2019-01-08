<?php

namespace Smartling\Tests\Unit;

use Smartling\AuditLog\AuditLogApi;
use Smartling\AuditLog\Params\CreateRecordParameters;
use Smartling\AuditLog\Params\SearchRecordParameters;

class AuditLogApiTest extends ApiTestAbstract
{
    /**
     * @covers \Smartling\AuditLog\AuditLogApi::createProjectLevelLogRecord
     */
    public function testCreateProjectLevelLogRecord()
    {
        $endpointUrl = vsprintf(
            '%s/projects/%s/logs',
            [
                AuditLogApi::ENDPOINT_URL,
                $this->projectId,
            ]
        );

        $createParams = (new CreateRecordParameters())
            ->setBucket("myBucket")
            ->setTime(1234567890)
            ->setActionType(CreateRecordParameters::ACTION_TYPE_UPLOAD)
            ->setUserId("myUserId")
            ->setDescription("myDescription")
            ->setCustomField("my_custom_field", "foo");

        $this->client->expects($this->any())
            ->method('request')
            ->with('post', $endpointUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => vsprintf('%s %s', [
                        $this->authProvider->getTokenType(),
                        $this->authProvider->getAccessToken(),
                    ]),
                ],
                'exceptions' => false,
                'json' => [
                    'time' => '2009-02-13T23:31:30Z',
                    'bucket' => 'myBucket',
                    'action_type' => CreateRecordParameters::ACTION_TYPE_UPLOAD,
                    'user_id' => 'myUserId',
                    'description' => 'myDescription',
                    'my_custom_field' => 'foo',
                ],
            ])
            ->willReturn($this->responseMock);

        $this->object->createProjectLevelLogRecord($createParams);
    }

    /**
     * @covers \Smartling\AuditLog\AuditLogApi::createAccountLevelLogRecord
     */
    public function testCreateAccountLevelLogRecord()
    {
        $accountUid = "account_uid";
        $endpointUrl = vsprintf(
            '%s/accounts/%s/logs',
            [
                AuditLogApi::ENDPOINT_URL,
                $accountUid,
            ]
        );

        $createParams = (new CreateRecordParameters())
            ->setBucket("myBucket")
            ->setTime(1234567890)
            ->setActionType(CreateRecordParameters::ACTION_TYPE_DOWNLOAD)
            ->setUserId("myUserId")
            ->setDescription("myDescription")
            ->setCustomField("my_custom_field", "foo");

        $this->client->expects($this->any())
            ->method('request')
            ->with('post', $endpointUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => vsprintf('%s %s', [
                        $this->authProvider->getTokenType(),
                        $this->authProvider->getAccessToken(),
                    ]),
                ],
                'exceptions' => false,
                'json' => [
                    'time' => '2009-02-13T23:31:30Z',
                    'bucket' => 'myBucket',
                    'action_type' => CreateRecordParameters::ACTION_TYPE_DOWNLOAD,
                    'user_id' => 'myUserId',
                    'description' => 'myDescription',
                    'my_custom_field' => 'foo',
                ],
            ])
            ->willReturn($this->responseMock);

        $this->object->createAccountLevelLogRecord($accountUid, $createParams);
    }

    /**
     * @covers \Smartling\AuditLog\AuditLogApi::searchProjectLevelLogRecord
     */
    public function testSearchProjectLevelLogRecord()
    {
        $endpointUrl = vsprintf(
            '%s/projects/%s/logs',
            [
                AuditLogApi::ENDPOINT_URL,
                $this->projectId,
            ]
        );

        $createParams = (new SearchRecordParameters())
            ->setSearchQuery("foo:bar")
            ->setOffset(1)
            ->setLimit(100)
            ->setSort("baz", SearchRecordParameters::ORDER_ASC);

        $this->client->expects($this->any())
            ->method('request')
            ->with('get', $endpointUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => vsprintf('%s %s', [
                        $this->authProvider->getTokenType(),
                        $this->authProvider->getAccessToken(),
                    ]),
                ],
                'exceptions' => false,
                'query' => [
                    'q' => 'foo:bar',
                    'offset' => 1,
                    'limit' => 100,
                    'sort' => 'baz:' . SearchRecordParameters::ORDER_ASC,
                ],
            ])
            ->willReturn($this->responseMock);

        $this->object->searchProjectLevelLogRecord($createParams);
    }

    /**
     * @covers \Smartling\AuditLog\AuditLogApi::searchAccountLevelLogRecord
     */
    public function testSearchAccountLevelLogRecord()
    {
        $accountUid = "account_uid";
        $endpointUrl = vsprintf(
            '%s/accounts/%s/logs',
            [
                AuditLogApi::ENDPOINT_URL,
                $accountUid,
            ]
        );

        $createParams = (new SearchRecordParameters())
            ->setSearchQuery("foo:bar")
            ->setOffset(1)
            ->setLimit(100)
            ->setSort("baz", SearchRecordParameters::ORDER_ASC);

        $this->client->expects($this->any())
            ->method('request')
            ->with('get', $endpointUrl, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => vsprintf('%s %s', [
                        $this->authProvider->getTokenType(),
                        $this->authProvider->getAccessToken(),
                    ]),
                ],
                'exceptions' => false,
                'query' => [
                    'q' => 'foo:bar',
                    'offset' => 1,
                    'limit' => 100,
                    'sort' => 'baz:' . SearchRecordParameters::ORDER_ASC,
                ],
            ])
            ->willReturn($this->responseMock);

        $this->object->searchAccountLevelLogRecord($accountUid, $createParams);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->prepareAuditLogApiMock();
    }

    private function prepareAuditLogApiMock()
    {
        $this->object = $this->getMockBuilder('Smartling\AuditLog\AuditLogApi')
            ->setMethods(NULL)
            ->setConstructorArgs([
                $this->projectId,
                $this->client,
                null,
                AuditLogApi::ENDPOINT_URL,
            ])
            ->getMock();

        $this->invokeMethod(
            $this->object,
            'setAuth',
            [
                $this->authProvider
            ]
        );
    }
}