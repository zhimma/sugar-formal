# Fingerprint #
=> 系統會根據使用者的request header資料，用雜湊的方式產生指紋碼

## Flowcharts ##
![](/fingerprint_JS.png)
> Sample code is in fingerprint.blade.php

## Table ##
- `id` int 流水編號
- `user_id` int user_id if need
- `fingerprintValue` varchar(32) 系統自動產生的指紋編號, 固定長度32字元的字串
-  `ipAddress` varchar(40) 使用者IP，目前未使用到
- `browser_name` varchar(30) 瀏覽器名稱 
- `browser_version` varchar(30) 瀏覽器的版本
- `os_name` varchar(30) 作業系統名稱
- `os_version` varchar(30) 作業系統版本
- `timezone` varchar(30) 時區
- `plugins` text 瀏覽器上的外掛套件, 為json格式
- `language` varchar(8) 瀏覽器使用的預設語言
- `created_at` timestamp
- `updated_at` timestamp

## Issue ##
- 若使用者的硬體設備為手機，會讓fingerprint的重疊率提高

