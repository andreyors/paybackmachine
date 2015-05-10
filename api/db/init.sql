CREATE DATABASE payback;

\c payback

CREATE TABLE events (id SERIAL, client_id VARCHAR(255), beacon_id INTEGER, created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT (NOW() AT TIME ZONE 'UTC'), updated_at TIMESTAMP WITHOUT TIME ZONE DEFAULT (NOW() AT TIME ZONE 'UTC'));

CREATE TABLE shops (id SERIAL, title VARCHAR(255), address VARCHAR(255), is_active BOOLEAN DEFAULT FALSE);

INSERT INTO shops (title, address, is_active) VALUES ('Betahaus', 'Prinzessenstr. 88', true);

CREATE TABLE beacons (id SERIAL, title VARCHAR(255), mac VARCHAR(17), minor INTEGER, major INTEGER, is_active BOOLEAN DEFAULT FALSE);

CREATE TABLE beacon_to_state(beacon_id INTEGER, state_id INTEGER);

CREATE TABLE states (id SERIAL, title VARCHAR(255), is_active BOOLEAN DEFAULT FALSE);

INSERT INTO states (title, is_active) VALUES ('in', true), ('out', true);

CREATE TABLE beacon_to_shop (shop_id INTEGER, beacon_id INTEGER);

CREATE TABLE users (id SERIAL, login VARCHAR(32), passwd VARCHAR(40), role_id INTEGER);

CREATE TABLE roles (id SERIAL, title VARCHAR(255), is_active BOOLEAN DEFAULT FALSE); 