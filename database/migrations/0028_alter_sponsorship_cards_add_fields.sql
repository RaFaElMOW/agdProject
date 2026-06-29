ALTER TABLE sponsorship_cards
    ADD COLUMN icon VARCHAR(50) NULL AFTER image,
    ADD COLUMN cta_link VARCHAR(255) NULL AFTER icon;
