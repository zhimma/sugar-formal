
ALTER TABLE `admin_common_text`
CHANGE COLUMN `alias` `alias` VARCHAR(60) NOT NULL;

INSERT INTO `admin_common_text` (`status`, `category`, `category_alias`, `alias`, `title`, `content`) VALUES
(1, '未分類', 'vip_text', 'atm_cvs_notice', 'VIP付費 ATM/CVS二次提示', '1：您選擇了ATM / 條碼 / 代碼繳費，由於金融業交換速度較慢，需等入帳後才能升級 vip。(時間約3~7天）\\n2：請務必「保存收據」或直接「上傳到站長 LINE 帳號」。'),
(1, '未分類', 'vip_text_red', 'atm_cvs_notice', 'VIP付費 ATM/CVS二次提示 紅字部分', '★若收據遺失又未上傳到站長 LINE 帳號，將以綠界金流紀錄為準，查不到則無法補發 VIP 。'),
(1, '未分類', 'hideOnline_text', 'atm_cvs_notice', '隱藏付費 ATM/CVS二次提示', '1：您選擇了ATM / 條碼 / 代碼繳費，由於金融業交換速度較慢，需等入帳後才能升級 vip。(時間約3~7天）\\n2：請務必「保存收據」或直接「上傳到站長 LINE 帳號」。'),
(1, '未分類', 'hideOnline_text_red', 'atm_cvs_notice', '隱藏付費 ATM/CVS二次提示 紅字部分', '★若收據遺失又未上傳到站長 LINE 帳號，將以綠界金流紀錄為準，查不到則無法補發 VIP 。');


