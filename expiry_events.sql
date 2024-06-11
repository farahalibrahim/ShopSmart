
-- delete expired credit cards
DELIMITER //
CREATE EVENT delete_expired_cards
ON SCHEDULE EVERY 1 DAY
STARTS NOW()
DO
BEGIN
    DELETE FROM `credit card` WHERE expiry < CURDATE();
END //
DELIMITER ;

-- delete expired coupons
DELIMITER //
CREATE EVENT delete_expired_coupons
ON SCHEDULE EVERY 1 DAY
STARTS NOW()
DO
BEGIN
    DELETE FROM `coupon code` WHERE `expiry` < CURDATE();
END //
DELIMITER ;

-- unfreeze users after freeze expiry
DELIMITER //
CREATE EVENT unfreeze_users
ON SCHEDULE EVERY 1 DAY
STARTS NOW()
DO
BEGIN
    UPDATE `user` 
    SET `account_status` = 'active', `freeze_expiry` = NULL, `freeze_reason` = NULL 
    WHERE `freeze_expiry` < CURDATE();
END //
DELIMITER ;

-- return product normal price after offer expiry
DELIMITER //
CREATE EVENT update_expired_offers
ON SCHEDULE EVERY 1 DAY
STARTS NOW()
DO
BEGIN
    UPDATE `product` 
    SET `offer` = false, `offer_percent` = NULL, `price` = `original_price`, `original_price` = NULL 
    WHERE `offer_expiry` < CURDATE();
END //
DELIMITER ;