-- DATA STRUCTURE TO COLLECT TS DATA
CREATE TABLE IF NOT EXISTS `l10nx_ts_captures` (
     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'ID',
     `timestamp`            timestamp           COMMENT 'when did this translation happen?',
     `locale`               varchar(5)          COMMENT 'the locale used',
     `context`              varchar(128)        COMMENT 'the context used',
     `original_hash`        varchar(40)         COMMENT 'sha1 of the original string',
     `original`             text                COMMENT 'the original',
     `translation`          text                COMMENT 'the translation',
    PRIMARY KEY ( `id` ),
    INDEX locale(locale),
    INDEX context(context),
    INDEX original_hash(original_hash),
    INDEX original(original(255))
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
