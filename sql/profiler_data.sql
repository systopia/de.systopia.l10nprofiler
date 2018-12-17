-- DATA STRUCTURE TO COLLECT TS DATA
CREATE TABLE IF NOT EXISTS `civicrm_l10nx_tsprofile` (
     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'ID',
     `timestamp`            timestamp           COMMENT 'when did this translation happen?',
     `count`                int unsigned        COMMENT 'number of occurrences',
     `locale`               varchar(5)          COMMENT 'the locale used',
     `context`              varchar(128)        COMMENT 'the context used',
     `original`             text                COMMENT 'the original',
     `translation`          text                COMMENT 'the translation',
    PRIMARY KEY ( `id` ),
    INDEX locale(locale),
    INDEX context(context),
    INDEX original(original(255))
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
