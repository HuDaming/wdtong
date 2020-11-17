<?php

namespace Hudm\Wdtong;

use Hudm\Wdtong\Support\WdtClient;

class Wdtong
{
    protected $sid;
    protected $appKey;
    protected $appSecret;
    protected $domain;

    public function __construct(array $config = [])
    {
        $this->sid = $config['sid'];
        $this->appKey = $config['key'];
        $this->appSecret = $config['secret'];
        $this->domain = $config['domain'];
    }

    public function getOrders($start, $end)
    {
        $client = new WdtClient();

        $client->sid = $this->sid;
        $client->appKey = $this->appKey;
        $client->appSecret = $this->appSecret;
        $client->gatewayUrl = $this->domain . '/openapi2/trade_query.php';

        $client->putApiParam('start_time', $start);
        $client->putApiParam('end_time', $end);

        return json_decode($client->wdtOpenApi(), true);
    }
}