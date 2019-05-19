-- таблица логов
DROP SEQUENCE IF EXISTS logs_id_seq CASCADE;
CREATE SEQUENCE logs_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
DROP TABLE IF EXISTS logs CASCADE;
CREATE TABLE logs (
                      id INTEGER DEFAULT nextval('logs_id_seq'::regclass) NOT NULL PRIMARY KEY,
                      error_code INTEGER DEFAULT NULL,
                      error_msg VARCHAR(512) DEFAULT NULL,
                      date TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- indexes
CREATE INDEX logs_error_code_idx ON logs(error_code);
CREATE INDEX logs_date_idx ON logs(date);
