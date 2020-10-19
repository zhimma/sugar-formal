ALTER TABLE payment_get_barcode_log
ADD column `isRead` tinyint(1) NULL DEFAULT 0 after TradeDate;