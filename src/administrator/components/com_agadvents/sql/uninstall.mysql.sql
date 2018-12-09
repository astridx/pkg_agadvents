DELETE FROM `#__content_types` WHERE `type_alias` IN ('com_agadvents.agadvent', 'com_agadvents.category');

DROP TABLE IF EXISTS `#__agadvents`;
