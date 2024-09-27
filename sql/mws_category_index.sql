CREATE TABLE /*$wgDBprefix*/mws_category_index (
    mci_cat_id INT UNSIGNED NOT NULL,
    mci_title VARCHAR(255) NOT NULL,
    mci_page_title BYTEA NOT NULL DEFAULT '',
    mci_count INT UNSIGNED NOT NULL DEFAULT 0
) /*$wgDBTableOptions*/;
