CREATE TABLE /*$wgDBprefix*/mws_title_index (
    mti_page_id SERIAL PRIMARY KEY,
    mti_namespace INT NOT NULL,
    mti_title VARCHAR(255) NOT NULL,
    mti_displaytitle VARCHAR(255) NOT NULL
) /*$wgDBTableOptions*/;
