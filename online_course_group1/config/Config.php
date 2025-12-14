<?php
class Config {

    const BASE_URL = 'http://localhost/Website_Quan_ly_khoa_hoc_online';
    const ASSET_URL = 'http://localhost/Website_Quan_ly_khoa_hoc_online/assets';
    const UPLOAD_URL = 'http://localhost/Website_Quan_ly_khoa_hoc_online/assets/uploads';
    
    public static function getBaseUrl() {
        return self::BASE_URL;
    }
    
    public static function getAssetUrl() {
        return self::ASSET_URL;
    }
    
    public static function getUploadUrl() {
        return self::UPLOAD_URL;
    }
}

function baseUrl($path = '') {
    return Config::getBaseUrl() . '/' . ltrim($path, '/');
}

function assetUrl($path = '') {
    return Config::getAssetUrl() . '/' . ltrim($path, '/');
}

function uploadUrl($path = '') {
    return Config::getUploadUrl() . '/' . ltrim($path, '/');
}
?>