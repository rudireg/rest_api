DROP TYPE IF EXISTS FILE_EXTENSION CASCADE;
CREATE TYPE FILE_EXTENSION AS ENUM (
    'jpg',
    'jpeg',
    'gif',
    'png',
    'bmp'
    );

-- Таблица хранения изображений
DROP SEQUENCE IF EXISTS images_id_seq CASCADE;
CREATE SEQUENCE images_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
DROP TABLE IF EXISTS images CASCADE;
CREATE TABLE images (
                        id INTEGER DEFAULT nextval('images_id_seq'::regclass) NOT NULL PRIMARY KEY,
                        width INTEGER NOT NULL,      -- ширина
                        height INTEGER NOT NULL,     -- высота
                        ext FILE_EXTENSION NOT NULL, -- расширение
                        url VARCHAR(128) NOT NULL    -- дата
);

-- indexes
CREATE INDEX images_width_idx ON images(width);
CREATE INDEX images_height_idx ON images(height);
CREATE INDEX images_ext_idx ON images(ext);
CREATE INDEX images_url_idx ON images(url);
