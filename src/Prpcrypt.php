<?php
namespace PhpRush\Wechat\Applet;

use Exception;

class Prpcrypt
{

    public $key;

    function __construct($k)
    {
        $this->key = $k;
    }

    /**
     * �����Ľ��н���
     *
     * @param $aesCipher ��Ҫ���ܵ�����            
     * @param $aesIV ���ܵĳ�ʼ����            
     * @return ���ܵõ�������
     */
    public function decrypt($aesCipher, $aesIV)
    {
        try {
            $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            mcrypt_generic_init($module, $this->key, $aesIV);
            // ����
            $decrypted = mdecrypt_generic($module, $aesCipher);
            mcrypt_generic_deinit($module);
            mcrypt_module_close($module);
        } catch (Exception $e) {
            return array(
                ErrorCode::$IllegalBuffer,
                null
            );
        }
        try {
            // ȥ����λ�ַ�
            $pkc_encoder = new PKCS7Encoder();
            $result = $pkc_encoder->decode($decrypted);
        } catch (Exception $e) {
            return array(
                ErrorCode::$IllegalBuffer,
                null
            );
        }
        return array(
            0,
            $result
        );
    }
}