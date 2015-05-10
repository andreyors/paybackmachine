CREATE DATABASE payback;

\c payback

CREATE TABLE events (id SERIAL, client_id VARCHAR(255), beacon_id INTEGER, created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT (NOW() AT TIME ZONE 'UTC'), updated_at TIMESTAMP WITHOUT TIME ZONE DEFAULT (NOW() AT TIME ZONE 'UTC'));

CREATE TABLE shops (id SERIAL, title VARCHAR(255), address VARCHAR(255), is_active BOOLEAN DEFAULT FALSE);

INSERT INTO shops (title, address, is_active) VALUES ('Betahaus', 'Prinzessenstr. 88', true);

CREATE TABLE beacons (id SERIAL, title VARCHAR(255), mac VARCHAR(17), minor INTEGER, major INTEGER, is_active BOOLEAN DEFAULT FALSE);

INSERT INTO beacons (title, mac, minor, major, is_active) VALUES ('Betahaus cashdesk', 'D1:A0:73:73:31:34', 29555, 12596, true);

CREATE TABLE beacon_to_state(beacon_id INTEGER, state_id INTEGER);

CREATE TABLE states (id SERIAL, title VARCHAR(255), is_active BOOLEAN DEFAULT FALSE);

INSERT INTO states (title, is_active) VALUES ('in', true), ('out', true);

CREATE TABLE beacon_to_shop (shop_id INTEGER, beacon_id INTEGER);

INSERT INTO beacon_to_shop SELECT (SELECT id FROM shops WHERE title = 'Betahaus'), (SELECT id FROM beacons WHERE title = 'Betahaus cashdesk');

CREATE TABLE users (id SERIAL, login VARCHAR(32), passwd VARCHAR(40), role_id INTEGER);

CREATE TABLE roles (id SERIAL, title VARCHAR(255), is_active BOOLEAN DEFAULT FALSE); 

INSERT INTO roles (title, is_active) VALUES ('Administrator', true), ('Manager', true), ('Seller', true), ('Customer', true);

INSERT INTO users (login, passwd, role_id) SELECT 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', (SELECT id FROM roles WHERE title = 'Administrator');

INSERT INTO users (login, passwd, role_id) SELECT 'customer', 'b39f008e318efd2bb988d724a161b61c6909677f', (SELECT id FROM roles WHERE title = 'Customer');
