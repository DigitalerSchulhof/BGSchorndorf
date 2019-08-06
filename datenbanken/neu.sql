INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES ($id, AES_ENCRYPT('Fehlermeldung an GitHub', '$CMS_SCHLUESSEL'), AES_ENCRYPT('1', '$CMS_SCHLUESSEL'))
