<?php

namespace Hudm\Wdtong\Support;

class WdtClient
{
    public $sid;
    public $appKey;
    public $appSecret;
    public $gatewayUrl = "";
    private $apiParas = array();

    private function packData(&$req)
    {
        ksort($req);
        $arr = array();
        foreach ($req as $key => $val) {
            if ($key == 'sign') continue;
            if (count($arr))
                $arr[] = ';';
            $arr[] = sprintf("%02d", iconv_strlen($key, 'UTF-8'));
            $arr[] = '-';
            $arr[] = $key;
            $arr[] = ':';
            $arr[] = sprintf("%04d", iconv_strlen($val, 'UTF-8'));
            $arr[] = '-';
            $arr[] = $val;
        }
        return implode('', $arr);
    }

    //加密生成sign
    private function makeSign(&$req, $appsecret)
    {
        $sign = md5($this->packData($req) . $appsecret);
        $req['sign'] = $sign;
    }

    private function check()
    {
        //请求参数校验
        if ($this->checkEmpty($this->sid))
            throw new \Exception('缺少必要请求参数【 sid 】', 40);
        if ($this->checkEmpty($this->appKey))
            throw new \Exception('缺少必要请求参数【 app key 】', 41);
        if ($this->checkEmpty($this->appSecret))
            throw new \Exception('【 app secret 】 未填写', 42);
    }

    private function checkEmpty($value)
    {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (is_array($value) && count($value) == 0)
            return true;
        if (is_string($value) && trim($value) === "")
            return true;

        return false;
    }

    public function putApiParam($key, $value)
    {
        if ($this->checkEmpty($value))
            throw new \Exception("传入参数【 $key 】 值为空", 46);
        $this->apiParas[$key] = $value;
    }

    public function putMultiApiparam($params)
    {
        $this->apiParas = array_merge($this->apiParas, $params);

    }

    public function wdtOpenApi()
    {
        //参数校验
        try {
            $this->check();
        } catch (\Exception $e) {
            return json_encode(['code' => $e->getCode(), 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }

        //参数封装
        $this->apiParas['sid'] = $this->sid;
        $this->apiParas['appkey'] = $this->appKey;
        $this->apiParas['timestamp'] = time();

        $this->makeSign($this->apiParas, $this->appSecret);

        $postData = http_build_query($this->apiParas, '', '&');
        $length = strlen($postData);

        $cl = curl_init($this->gatewayUrl);
        curl_setopt($cl, CURLOPT_POST, true);
        curl_setopt($cl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($cl, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-length: " . $length));
        curl_setopt($cl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($cl, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($cl);
        if (curl_errno($cl)) {
            echo "Error: " . curl_error($cl);
        }
        curl_close($cl);

        $json = json_encode(json_decode($content));
        if (!$json) {
            var_dump($content);
            return NULL;
        }
        return $json;

    }
}

?>