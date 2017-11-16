<?php
namespace PhpRush\Wechat\Applet;

class PKCS7Encoder
{

    public static $block_size = 16;

    /**
     * ����Ҫ���ܵ����Ľ�����䲹λ
     * 
     * @param $text ��Ҫ������䲹λ����������            
     * @return ���������ַ���
     */
    function encode($text)
    {
        $block_size = PKCS7Encoder::$block_size;
        $text_length = strlen($text);
        // ������Ҫ����λ��
        $amount_to_pad = PKCS7Encoder::$block_size - ($text_length % PKCS7Encoder::$block_size);
        if ($amount_to_pad == 0) {
            $amount_to_pad = PKCS7Encoder::block_size;
        }
        // ��ò�λ���õ��ַ�
        $pad_chr = chr($amount_to_pad);
        $tmp = "";
        for ($index = 0; $index < $amount_to_pad; $index ++) {
            $tmp .= $pad_chr;
        }
        return $text . $tmp;
    }

    /**
     * �Խ��ܺ�����Ľ��в�λɾ��
     * 
     * @param decrypted ���ܺ������
     * @return ɾ����䲹λ�������
     */
    function decode($text)
    {
        $pad = ord(substr($text, - 1));
        if ($pad < 1 || $pad > 32) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }
}