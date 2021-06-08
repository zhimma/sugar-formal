-- --------------------------------------------------------

--
-- 資料表結構 `admin_menu_items`
--

CREATE TABLE `admin_menu_items` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `route_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `admin_menu_items`
--

INSERT INTO `admin_menu_items` (`id`, `title`, `route_path`, `created_at`, `updated_at`) VALUES
(1, '自動封鎖警示設定', '/admin/stats/set_autoBan', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(2, 'VIP會員統計資料', '/admin/stats/vip', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(3, '付費 VIP 會員訂單資料', '/admin/stats/vip/paid', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(4, '其他 VIP 相關統計資料', '/admin/stats/other', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(5, '綠界 VIP 付費取消資料', '/admin/users/VIP/ECCancellations', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(6, '會員列表查詢', '/admin/users/memberList', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(7, '會員搜尋(變更男女、VIP資料)', '/admin/users/search', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(8, '進階會員搜尋', '/admin/users/advSearch', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(9, '會員封鎖清單', '/admin/users/bannedList', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(10, '指紋比對清單', '/admin/users/banned_implicitly', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(11, '警示名單', '/admin/users/warning', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(12, '多重登入名單', '/admin/users/multiple-login', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(13, '異常連線記錄', '/admin/too_many_requests', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(14, '異常連線記錄(純記錄)', '/admin/too_many_requests?pseudo=1', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(15, '疑似多重登入名單', '/admin/users/suspectedMultiLogin', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(16, '會員照片管理', '/admin/users/pictures', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(17, '會員照片管理簡化版', '/admin/users/picturesSimple', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(18, '會員被檢舉次數', '/admin/users/reported/count', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(19, '留言板管理', '/admin/users/board', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(20, '討論區管理', '/admin/users/posts', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(21, '會員訊息管理', '/admin/users/message/search', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(22, '會員訊息統計', '/admin/statistics', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(23, '罐頭訊息查詢', '/admin/users/spam_text_message/search', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(24, '被檢舉會員清單', '/admin/users/reported', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(25, '被檢舉照片清單', '/admin/users/pics/reported', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(26, '基本設定', '/admin/users/basic_setting', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(27, '修改會員密碼', '/admin/users/changePassword', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(28, '切換會員身份', '/admin/users/switch', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(29, '關閉會員帳號原因統計', '/admin/users/closeAccountReason', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(30, '未啟動會員', '/admin/users/inactive', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(31, '指定會員發送訊息', '/admin/users/message/sendUserMessage', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(32, '站長公告', '/admin/announcement', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(33, '站長的話', '/admin/masterwords', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(34, '網站公告本月封鎖名單', '/admin/web/announcement', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(35, '站長信箱', '/admin/chat', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(36, '編輯文案', '/admin/commontext', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(37, '站長審核', '/admin/check', '2021-06-04 06:53:43', '2021-06-04 06:53:43'),
(38, 'Admin後台操作記錄', '/admin/getAdminActionLog', '2021-06-04 06:53:43', '2021-06-04 06:53:43');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `admin_menu_items`
--
ALTER TABLE `admin_menu_items`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `admin_menu_items`
--
ALTER TABLE `admin_menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;




ALTER TABLE `role_user` ADD `created_at` TIMESTAMP NULL AFTER `item_permission`, ADD `updated_at` TIMESTAMP NULL AFTER `created_at`;
ALTER TABLE role_user  drop foreign key role_user_role_id_foreign;
ALTER TABLE role_user  drop foreign key role_user_user_id_foreign;
