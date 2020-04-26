DELETE FROM rollen WHERE id = 7;

-- 0.6

DELETE FROM rollen WHERE id = 7;

-- 0.7

DELETE FROM rollen WHERE AES_DECRYPT(bezeichnung, {cms_schluessel}) = 'c';
